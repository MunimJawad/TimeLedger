<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;

class CommentController extends Controller
{
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
}
