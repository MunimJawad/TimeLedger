@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold">Add Task to {{ $project->name }}</h2>

<form method="POST" action="{{ route('projects.task.store', $project->id) }}">
    @csrf
    <label>Title</label>
    <input type="text" name="title" class="border p-2 w-full mb-3">

    <label>Description</label>
    <textarea name="description" class="border p-2 w-full mb-3"></textarea>

    <label>Assign To</label>
    <select name="assigned_to" class="border p-2 w-full mb-3">
        <option value="">-- None --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <label>Collaborators</label>
    <select name="collaborators[]" multiple class="border p-2 w-full mb-3">
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection
