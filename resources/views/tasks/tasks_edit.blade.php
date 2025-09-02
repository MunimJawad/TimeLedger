@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold"> {{ $project->name }}</h2>

<form method="POST" action="{{ route('projects.task.update',[$project,$task]) }}">
    @csrf
    @method('PUT')
    <label>Title</label>
    <input type="text" value="{{ $task->title }}" name="title" class="border p-2 w-full mb-3">

    <label>Description</label>
    <textarea name="description" class="border p-2 w-full mb-3">{{ $task->description }}</textarea>

    <label for="">Status</label>
    <select name="status" class="border p-2 w-full mb-3">
            <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
    </select>

    <label>Assign To</label>
    <select name="assigned_to" class="border p-2 w-full mb-3">
        <option value="">-- None --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" @if($task->assigned_to == $user->id) selected @endif>{{ $user->name }}</option>
        @endforeach
    </select>

    <label>Collaborators</label>
    <select name="collaborators[]" multiple class="border p-2 w-full mb-3">
        @foreach($users as $user)
            <option value="{{ $user->id }}" @if ($task->collaborators->contains($user->id)) selected @endif>{{ $user->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection
