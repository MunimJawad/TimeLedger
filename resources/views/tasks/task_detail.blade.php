@extends('layouts.app')

@section('title','Task Detail')
@section('header','Task Detail')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow-sm">
    <h1 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h1>
    <div class="text-sm text-gray-500 mb-4">
        <strong class="text-blue-700">{{ $task->assignee?->name }}</strong> · 
        <span>{{ $task->created_at->format('M d, Y') }}</span>
    </div>
    <p class="text-gray-700 leading-relaxed mb-6">{{ $task->description }}</p>

    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">💬 Comments</h2>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Comment Form -->
    <form action="{{ route('tasks.comment.store', $task->id) }}" method="POST" enctype="multipart/form-data" class="mb-8 space-y-4">
        @csrf
        <textarea 
            name="content" 
            rows="3" 
            class="w-full border border-gray-300 p-3 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none" 
            placeholder="Write a comment and tag users with @username" 
            required>{{ old('content') }}</textarea>

        <input type="file" name="file" class="block w-full text-sm text-gray-500 mt-2">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded shadow">
            ➕ Add Comment
        </button>
    </form>

    <!-- Comments List -->
    @forelse ($task->comments->sortByDesc('created_at') as $comment)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
            <div class="text-sm text-gray-500 mb-2 flex justify-between">
                <div>
                <strong class="text-indigo-600">{{ $comment->user->name }}</strong> · 
                <span>{{ $comment->created_at->diffForHumans() }}</span></div>
                @if (request()->user()?->id == $comment->user->id || request()->user()?->role=='admin')
                <div class="flex space-x-2">
                    <a href="{{ route('tasks.comment.edit', [$task,$comment]) }}" class= "bg-blue-500 w-fit px-3 py-1 mt-2 text-white rounded">Edit</a>
                   <form action="{{ route('tasks.comment.destroy', [$task, $comment]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-500 w-fit px-3 py-1 mt-2 text-white rounded">Delete</button>
</form>
                     
                </div>
                @endif
                
            </div>

            <div class="text-gray-800 leading-relaxed">
                {!! preg_replace('/@(\w+)/', '<span class="text-blue-500 font-semibold">@\$1</span>', e($comment->content)) !!}
            </div>

            @if ($comment->file)
                <div class="mt-3">
                    @if (Str::endsWith($comment->file, ['.jpg', '.jpeg', '.png', '.gif']))
                        <img src="{{ asset('storage/' . $comment->file) }}" alt="Comment Image" class="w-48 rounded shadow">
                    @else
                        <a href="{{ asset('storage/' . $comment->file) }}" target="_blank" class="text-blue-600 hover:underline">
                            📎 Download Attachment
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-500 italic">No comments yet. Be the first to add one!</p>
    @endforelse
</div>
@endsection
