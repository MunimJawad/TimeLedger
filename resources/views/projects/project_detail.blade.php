@extends('layouts.app')
@section('title', 'Project Detail')

@section('content')
    <div class="w-full mx-auto bg-white shadow-md rounded-lg p-6 space-y-4">
        <h1 class="text-2xl font-bold text-gray-800">{{ $project->name }}</h1>

        <div class="text-sm text-gray-500">
            <span class="font-medium text-gray-700">Owner:</span> {{ $project->owner->name ?? 'Not assigned yet'}} |
            <span class="ml-2">{{ $project->created_at->format('M d, Y') }}</span>
        </div>

        <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Members</h2>
            @if ($project->members->isNotEmpty())
                <ul class="space-y-1">
                    @foreach ($project->members as $member)
                        <li class="text-gray-700 pl-3 relative before:absolute before:left-0 before:top-2 before:h-1.5 before:w-1.5 before:rounded-full before:bg-slate-500">
                            {{ $member->name }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 italic">No members assigned to this project.</p>
            @endif
        </div>
       
        
    </div>

    <div class="container mx-auto px-4 py-20">

   {{-- Tasks List Header --}}
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

    {{-- Heading --}}
    <h1 class="text-2xl font-bold text-gray-800">
        Tasks List
    </h1>

    {{-- Search & Filter Form --}}
    <div class="w-full max-w-3xl">
        <form action="{{ route('projects.show', $project) }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">

            {{-- Search Input --}}
            <div class="relative flex-1 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    class="absolute w-5 h-5 left-3 top-2.5 text-gray-400 pointer-events-none" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                        clip-rule="evenodd" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search tasks..."
                    class="w-full bg-white border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                />
            </div>

            {{-- Status Filter --}}
            <div class="flex items-center gap-2">
                <label for="status" class="text-sm font-medium text-gray-700 whitespace-nowrap">Status:</label>
                <select name="status" id="status"
                    class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    onchange="this.form.submit()">
                    <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            {{-- Search Button --}}
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
            >
                Search
            </button>
        </form>
    </div>

    {{-- Create Task Button --}}
    @auth
        @if (auth()->user()?->role == 'admin' || auth()->user()->id == $project->owner?->id)
            <a href="{{ route('projects.task.create', $project) }}"
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow-md text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                + Create Task
            </a>
        @endif
    @endauth
</div>

    <!--Task table-->
    <table class="table-auto w-full mt-4 border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Title</th>
            <th class="border px-4 py-2">Assignee</th>
            <th class="border px-4 py-2">Collaborators</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Created</th>
            @auth
             @if (auth()->user()->id==$project->owner?->id || auth()->user()->role=='admin' )
                  <th class="border px-4 py-2">Actions</th>
             @endif           
                
            @endauth
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td class="border px-4 py-2">{{ $task->title }}</td>
            <td class="border px-4 py-2">{{ $task->assignee?->name ?? 'Unassigned' }}</td>
            <td class="border px-4 py-2">
                @foreach($task->collaborators as $collab)
                    <span class="bg-gray-200 px-2 py-1 rounded">{{ $collab->name }}</span>
                @endforeach
            </td>
            <td class="border px-4 py-2">{{ $task->status }}</td>
            <td class="border px-4 py-2">{{ $task->created_at }}</td>

             @auth
             @if (auth()->user()->id==$project->owner?->id || auth()->user()->role=='admin')
                  <td class="border px-4 py-2">
                     <a href="{{ route('projects.task.edit',[$project,$task]) }}" class="bg-blue-500 px-3 py-1 text-white rounded">Edit</a>
               
                     @if (!$task->deleted_at)                            
                 <form action="{{ route('projects.task.destroy', [$project, $task]) }}" method="POST" class="inline-block">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Are you sure?')" class="bg-red-500 px-3 py-1 text-white rounded">Delete</button>
                </form>

                @else
                 <form action="{{ route('projects.task.restore', [$project->id, $task->id]) }}" method="POST" class="inline-block">
                    @csrf @method('PATCH')
                    <button onclick="return confirm('Are you sure?')" class="bg-purple-500 px-3 py-1 text-white rounded">Restore</button>
                </form>
                 @endif

            </td>
             @endif           
                
            @endauth
            
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $tasks->links() }}
</div>
@endsection
