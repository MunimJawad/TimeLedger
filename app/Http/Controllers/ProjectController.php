<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectController extends Controller
{   
   public function index(Request $request)
{
    $query = $request->input('search');
    $user = Auth::user();

    $projects = Project::with('owner','members')
        ->when($user->role !== 'admin', function ($builder) use ($user) {
            // If not admin: show only projects user owns or is a member of
            $builder->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id) // Make sure this matches your DB field
                  ->orWhereHas('members', function ($memberQuery) use ($user) {
                      $memberQuery->where('users.id', $user->id);
                  });
            });
        })
        ->when($query, function ($builder) use ($query) {
            // Apply search to project name or owner name
            $builder->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhereHas('owner', function ($q2) use ($query) {
                      $q2->where('name', 'like', '%' . $query . '%');
                  });
            });
        })
        ->paginate(10)
        ->appends(['search' => $query]);

    return view('projects.projects_index', compact('projects'));
}

    public function show(Project $project){
        return view('projects.project_detail',compact('project'));
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
                'members.*'=>'exists:users,id'
            ]
         );

         $project=Project::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'owner_id'=>$request->owner_id
         ]);

          if ($request->has('members')) {
        $project->members()->attach($request->members);
    }

         return redirect()->route('projects.index')->with('success','Project Created Successfully');
    }

    public function edit(Project $project){
         $users = User::all();
        return view('projects.project_edit',compact('project','users'));
    }

  public function update(Request $request, Project $project)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'owner_id' => 'required|exists:users,id',
        'members' => 'nullable|array',
        'members.*' => 'exists:users,id',
    ]);

    // Update project fields
    $project->update($request->only('name', 'description', 'owner_id'));

    // Sync members (replaces existing members with new selection)
    $project->members()->sync($request->members ?? []);

    return redirect()->route('projects.index')->with('success', 'Project Updated Successfully');
}


    public function destroy(Project $project){
        $project->delete();
        return redirect()->back()->with('success','Post Deleted Successfully');
    }
}
