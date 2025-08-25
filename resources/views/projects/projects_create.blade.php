@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-xl">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Create a New Project</h1>

        <form action="{{ route('projects.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Project Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50"
                    placeholder="Enter project name"
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
                ></textarea>
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
        <option value="" disabled selected>Select a user</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
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
    >
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">Hold Ctrl to select multiple members.</p>
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
