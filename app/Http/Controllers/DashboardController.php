<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index(){
     $user=Auth::user();

     $projects = Project::query()->with(['tasks', 'members']);

      if ($user->role !== 'admin') {
          $projects->where(function($query) use ($user) {
              $query->where('owner_id', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
          });
      }

     $projects = $projects->get(); // fetches the filtered results
     $stats=[];

    foreach($projects as $project){
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('status','done')->count();
        $pendingTasks=$totalTasks-$completedTasks;

        $progress= $totalTasks>0?round(($completedTasks/$totalTasks)*100,2):0;

        $stats[]=[
            'project'=>$project,
            'total_tasks'=>$totalTasks,
            'completed_tasks'=>$completedTasks,
            'pending_tasks'=>$pendingTasks,
            'progress'=>$progress,
            'members_count'=>$project->members->count()
        
        ];
    }

    return view('dashboard',compact('stats'));

   }

}
