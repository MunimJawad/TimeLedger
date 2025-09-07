<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Gate;
use App\Notifications\TaskActivityNotification;


class CommentController extends Controller
{
    use Authorizable;
    public function store(Request $request, Task $task)
    {
        // Validate input, file max 2MB
        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $path = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Check if uploaded file is valid
            if (!$file->isValid()) {
                return back()->withErrors(['file' => 'Invalid file upload'])->withInput();
            }

            // Store file in 'comments' folder on 'public' disk
            $path = $file->store('comments', 'public');
        }

        // Create comment record
        Comment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'file' => $path,
        ]);



        // Redirect back to the task detail page with success message
        return redirect()->route('projects.task.show', [
            'project' => $task->project_id,
            'task' => $task->id,
        ])->with('success', 'Comment Added Successfully');
    }

    public function edit(Project $project,Task $task, Comment $comment)
    {
         
        return view('tasks.comment_edit', compact('task', 'comment'));  // Corrected the compact parameters
    }

public function update(Request $request, Task $task, Comment $comment)
{
    
    Gate::authorize('update', $comment);
    $project=$task->project;

    // Validate content
    $request->validate([
        'content' => 'required|string',
        'file' => 'nullable|file|max:10240', // Add validation for file
    ]);

    // Handle the file upload if it's present
    $path = $comment->file;  // Default to the current file if no new one is uploaded

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        // Check if uploaded file is valid
        if (!$file->isValid()) {
            return back()->withErrors(['file' => 'Invalid file upload'])->withInput();
        }

        // Store the new file and update the file path
        $path = $file->store('comments', 'public');
    }

    // Update the comment's content and file (if new file uploaded)
    $comment->update([
        'content' => $request->content,
        'file' => $path,
    ]);

    // Flash the task to the session
    session()->flash('task', $task);

 
    // Redirect back to the task's page with success message
    return redirect()->route('projects.task.show',  [$project,$task])
        ->with('success', 'Comment updated successfully.');
}

public function destroy(Task $task, Comment $comment)
{
    // Get the associated project
    Gate::authorize('update', $comment);
    $project = $task->project;

    // Delete the comment
    $comment->delete();

    // Redirect to the task's page with success message
    return redirect()->route('projects.task.show', [$project, $task])
         ->with('success', 'Comment deleted successfully.');
}

}
