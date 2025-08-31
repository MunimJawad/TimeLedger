<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
      public function create(Project $project) {
        $users = $project->members;
        return view('tasks.tasks_create', compact('project', 'users'));
    }

    public function store(Request $request, Project $project) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'collaborators' => 'array',
            'collaborators.*' => 'exists:users,id'
        ]);

        $task = $project->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        if (!empty($data['collaborators'])) {
            $task->collaborators()->attach($data['collaborators']);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Task created successfully.');
    }

    public function show(Project $project,Task $task){
       return view('tasks.task_detail',compact('task','project'));
    }

     public function destroy(Project $project, Task $task) {
        $task->delete();
        return redirect()->route('projects.show', $project)->with('success', 'Task deleted successfully.');
    }

   public function restore(Project $project, $taskId)
{
    $task = Task::withTrashed()->where('id', $taskId)->firstOrFail();

    $task->restore();

    return redirect()->route('projects.show', $project)->with('success', 'Task restored successfully');
}

    public function edit(Project $project,Task $task){
        $users=$project->members;
        return view('tasks.tasks_edit',compact('project','task','users'));
    }

    public function update(Request $request,Project $project,Task $task){
         $data=$request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'=>'required|in:todo,in_progress,done',
            'assigned_to' => 'nullable|exists:users,id',
            'collaborators' => 'array',
            'collaborators.*' => 'exists:users,id'
         ]);

         $task->update([
            'title'=>$data['title'],
            'description'=>$data['description'],
            'status'=>$data['status'],
            'assigned_to'=>$data['assigned_to']
         ]);

         $task->collaborators()->sync($data['collaborators']  ??  []);
         return redirect()->route('projects.show',$project)->with('success','Project updated successfully');
    }
}
