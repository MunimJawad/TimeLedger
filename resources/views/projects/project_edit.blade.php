@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-xl">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Project Edit</h1>

        <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Project Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50"
                    placeholder="Enter project name"
                    value="{{ old('name', $project->name) }}"
                    required
                >
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50"
                    placeholder="Enter a brief description..."
               
                >{{ $project->description }}</textarea>
            </div>

              {{-- Deadline Selection --}}
             <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
               <input
                    type="date"
                    name="deadline"
                    id="deadline"
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50"
                    value="{{ $project->deadline }}"
                >
            </div>

            {{-- Owner Selection --}}
<div>
    <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
    <select
        name="owner_id"
        id="owner_id"
        class="w-full p-2 border border-gray-300 rounded bg-gray-50"
        required
    >
        <option value="" disabled {{ old('owner_id', $project->owner_id) ? '' : 'selected' }}>Select a user</option>

        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('owner_id', $project->owner_id) == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
  <label for="members" class="block text-sm font-medium text-gray-700 mb-1">Members</label>
<select
    name="members[]"
    id="members"
    multiple
    class="w-full p-2 border border-gray-300 rounded bg-gray-50"
    required
>
    <option value="" disabled {{ old('members', $project->members->pluck('id')->toArray()) ? '' : 'selected' }}>Select a user</option>

    @foreach($users as $user)
        <option value="{{ $user->id }}"
            {{ in_array($user->id, old('members', $project->members->pluck('id')->toArray())) ? 'selected' : '' }}>
            {{ $user->name }}
        </option>
    @endforeach
</select>

</div>

            {{-- Submit Button --}}
            <div class="text-right">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded transition duration-150"
                >
                    Save Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
