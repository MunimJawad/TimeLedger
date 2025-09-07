<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

use App\Notifications\ProjectCreated;
use Illuminate\Support\Facades\Notification;

class ProjectController extends Controller
{   
   public function index(Request $request)
{
    $query = $request->input('search');
    $status = $request->input('status', 'active'); // default to 'active'
    $user = Auth::user();

    // Start base query (without soft deletes yet)
    $projectsQuery = Project::query()
        ->with('owner', 'members');

    // Apply soft delete filtering based on 'status'
    if ($status === 'inactive') {
        $projectsQuery->onlyTrashed();
    } elseif ($status === 'all') {
        $projectsQuery->withTrashed();
    } // else: active (default) — do nothing, just don't include trashed

    // User-based access control
    if ($user->role !== 'admin') {
        $projectsQuery->where(function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('members', function ($memberQuery) use ($user) {
                  $memberQuery->where('users.id', $user->id);
              });
        });
    }

  // Search filter
if ($query) {
    $projectsQuery->where(function ($queryBuilder) use ($query) {
        $queryBuilder->where('name', 'like', '%' . $query . '%')
            ->orWhereHas('owner', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhereHas('members', function ($memberQuery) use ($query) {
                $memberQuery->where('name', 'like', '%' . $query . '%');
            });
    });
}

    $count=$projectsQuery->count();

      $projects = $projectsQuery
        ->paginate(10)
        ->appends([
            'search' => $query,
            'status' => $status
        ]);

        
    return view('projects.projects_index', compact('projects', 'status','count'));
}


    public function show(Request $request,Project $project){
      
       
       $search=$request->input('search');      
       $status=$request->input('status'); 
       $tasksQuery = $project->tasks()->with(['assignee', 'collaborators'])->latest();

         // Apply soft delete filtering based on 'status'
    if ($status === 'inactive') {
       $tasksQuery->onlyTrashed();
    } elseif ($status === 'all') {
        $tasksQuery->withTrashed();
    } // else: active (default) — do nothing, just don't include trashed


       if($search){
          
           $tasksQuery->where(function($query) use ($search){
               $query->where('title', 'like', '%' . $search . '%')
               ->orWhereHas('assignee',function($q) use($search){
                $q->where('name', 'like', '%' . $search . '%');
               });
           });
       }

       $tasks=$tasksQuery->paginate(10)->withQueryString();

       //Visual analytics of indivual project

   // Stats
    $totalTasks = $tasks->count();
    $completedTasks = $tasks->where('status', 'done')->count(); // Make sure this matches your enum
    $pendingTasks = $tasks->where('status', 'todo')->count();
    $inProgressTasks = $tasks->where('status', 'in_progress')->count();

    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

    // Tasks by deadline (group by month)
    $tasksByMonth = $tasks->groupBy(function($task) {
        return \Carbon\Carbon::parse($task->due_date)->format('F');
    })->map->count();

    // Tasks per member
    $tasksPerMember = $tasks->groupBy('assigned_to')->map->count();

    // Pack all stats into one array
    $stats = [
        'totalTasks' => $totalTasks,
        'completedTasks' => $completedTasks,
        'pendingTasks' => $pendingTasks,
        'inProgressTasks' => $inProgressTasks,
        'progress' => $progress,
        'tasksByMonth' => $tasksByMonth,
        'tasksPerMember' => $tasksPerMember,
    ];
       

        return view('projects.project_detail',compact('project','tasks','stats'));
    }


    public function create(){
        $users = User::all();
        return view('projects.projects_create',compact('users'));
    }


    public function store(Request $request){
         $request->validate(
            [
                'name'=>'required|string|max:255',
                'description'=>'nullable|string',
                'owner_id' => 'required|exists:users,id',
                'members'=>'nullable|array',
                'members.*'=>'exists:users,id',
                'deadline' => 'nullable|date|after_or_equal:today',
            ]
         );

         $project=Project::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'owner_id'=>$request->owner_id,
            'deadline'=>$request->deadline
         ]);

          if ($request->has('members')) {
        $project->members()->attach($request->members);
    }

    //Notification System
        $adminUsers=User::where('role','admin')->get();
        $owner=User::find($request->owner_id);

        $members=Collect();

        if($request->has('members')){
          $members=User::whereIn('id',$request->members)->get();
        }

        $allToNotify=$adminUsers->merge($members)->push($owner)->unique('id');
        $message = 'Project "' . $project->name . '" created.';

        Notification::send($allToNotify,new ProjectCreated($message,$project));

         return redirect()->route('projects.index')->with('success','Project Created Successfully');
    }

    

    public function edit(Project $project){
         $users = User::all();
        return view('projects.project_edit',compact('project','users'));
    }



  public function update(Request $request, Project $project)
{
    $previousName=$project->name;
    $previousowner=$project->owner_id;
    $previousMembers=$project->members->pluck('id')->sort()->values()->all();

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'owner_id' => 'required|exists:users,id',
        'members' => 'nullable|array',
        'members.*' => 'exists:users,id',
        'deadline' => 'nullable|date|after_or_equal:today',
    ]);

    // Update project fields
    $project->update($request->only('name', 'description', 'owner_id','deadline'));

    // Sync members (replaces existing members with new selection)
    $project->members()->sync($request->members ?? []);

    //Get the admin users

    $adminUsers=User::where('role','admin')->get();
    $owner=$project->owner;
    $members=$project->members;
    $allToNotify=$adminUsers->merge($members)->push($owner)->unique('id');

    //convert new requested members to array

    $newMembers = collect($request->members ?? [])->sort()->values()->all();

    if ($previousName !== $request->name) {
          $message = 'Name of Project "' . $project->name . '" updated.';
    }elseif ((int) $previousowner !== (int) $request->owner_id) {
          $message = 'Manager of Project "' . $project->name . '" updated.';
    }elseif ($previousMembers !== $newMembers) {
        $addedMembers=array_diff($newMembers,$previousMembers);
        $removedMembers = array_diff($previousMembers, $newMembers);

        $addedMemberNames=User::whereIn('id',$addedMembers)->pluck('name')->toArray();
        $removedMemberNames=User::whereIn('id',$removedMembers)->pluck('name')->toArray();
        $parts=[];
        if(!empty($addedMemberNames)){
            $parts[]='Added members name: '.implode(', ',$addedMemberNames);
        }

        if(!empty($removedMemberNames)){
            $parts[]='Removed Members: '.implode(', ',$removedMemberNames);
        }

        $message = 'Members of Project "' . $project->name . '" updated. ' . implode('; ', $parts);
    }else {
          $message = 'Project "' . $project->name . '" updated.';
      }
    Notification::send($allToNotify,new ProjectCreated($message,$project));

    return redirect()->route('projects.index')->with('success', 'Project Updated Successfully');
}

    public function destroy(Project $project){
       // Get related users BEFORE deleting the project
    $adminUsers = User::where('role', 'admin')->get();
    $owner = $project->owner; // Assuming relation exists: $project->owner
    $members = $project->members; // Assuming relation exists: $project->members()

    $allToNotify = $adminUsers->merge($members)->push($owner)->unique('id');

    $project->delete(); // Now safe to delete

    $message = 'Project "' . $project->name . '" was deleted.';

    Notification::send($allToNotify, new ProjectCreated($message,$project));

        return redirect()->back()->with('success','Project Deleted Successfully');
    }

   public function restore($projectID)
     {
        $project = Project::withTrashed()->findOrFail($projectID);

        $adminUsers = User::where('role', 'admin')->get();
        $owner = $project->owner; // Assuming relation exists: $project->owner
        $members = $project->members; // Assuming relation exists: $project->members()

        $allToNotify = $adminUsers->merge($members)->push($owner)->unique('id');

        $project->restore(); // Now safe to delete

        $message = 'Project "' . $project->name . '" was restored.';
        
        Notification::send($allToNotify, new ProjectCreated($message,$project));
     
        return redirect()->route('projects.index')->with('success', 'Project Restored Successfully');
     }
}
