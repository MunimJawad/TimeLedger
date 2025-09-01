@extends('layouts.app')

@section('title', 'Edit Comment')

@section('content')
  <div class="max-w-4xl mx-auto py-8">
    <!-- Display Success Message -->
    @if(session('success'))
      <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('tasks.comment.update', [$task, $comment]) }}" method="POST" enctype="multipart/form-data" class="mb-8 space-y-4">
    @csrf
    @method('PUT')

    <textarea 
        name="content" 
        rows="3" 
        class="w-full border border-gray-300 p-3 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none" 
        placeholder="Write a comment and tag users with @username" 
        required>{{ old('content', $comment->content) }}</textarea>

    <input type="file" name="file" class="block w-full text-sm text-gray-500 mt-2">

    <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded shadow">
        ➕ Update Comment
    </button>
</form>

  </div>
@endsection
