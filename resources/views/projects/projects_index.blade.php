@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    
<div class="container mx-auto px-4 py-4">

    {{-- Create Button --}}
   {{-- Header and Search/Filter --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    {{-- Heading with project count --}}
    <h1 class="text-2xl font-bold text-gray-800">
        Project List <span class="text-blue-600">({{ $count }})</span>
    </h1>

    {{-- Search & Filter Form --}}
    <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full max-w-2xl">
        {{-- Search Box --}}
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                class="absolute w-5 h-5 top-2.5 left-3 text-slate-400">
                <path fill-rule="evenodd"
                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                    clip-rule="evenodd" />
            </svg>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search projects..."
                class="w-full bg-white border border-gray-300 text-sm text-gray-700 rounded-md pl-10 pr-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                oninput="this.form.submit()" />
        </div>

        {{-- Status Filter --}}
        <div class="flex items-center gap-2">
            <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
            <select name="status" id="status"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                onchange="this.form.submit()">
                <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
            </select>
        </div>

        {{-- Optional Manual Submit Button --}}
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md shadow transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
            Search
        </button>
    </form>

        @auth
        @if (auth()->user()?->role =='admin') 
            <a href="{{ route('projects.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition duration-200 text-sm font-medium">
            + Create Project
          </a>
        @endif
        @endauth
        
    </div>

    {{-- Projects Grid/List --}}
    <div class="space-y-4">
        @forelse ($projects as $project)
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <a href="{{ route('projects.show',$project->id) }}"><h2 class="text-lg text-blue-600 font-semibold">{{ $project->name }}</h2></a>
                <p class="text-gray-600 text-sm mt-1">{{ $project->description ?? 'No description provided.' }}</p>
                <div class="mt-3 text-sm text-gray-500 mb-2">
                    <span class="font-medium text-gray-700">Manager:</span> {{ $project->owner->name ?? 'N/A' }}
                </div>
                 <div class="flex space-x-4">
                   <a href="{{ route('projects.show', $project->id) }}"  class="bg-yellow-700 px-3 py-1 text-white rounded"><button >Detail</button></a>
                 @auth
        @if (auth()->user()?->role =='admin' || auth()->user()->id==$project->owner?->id) 
                <a href="{{ route('projects.edit', $project->id) }}"  class="bg-emerald-600 px-3 py-1 text-white rounded"><button >Edit</button></a>

          @endif
            @if(auth()->user()?->role =='admin')
            
            @if (!$project->deleted_at)
          <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure to delete this project?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 px-3 py-1 text-white rounded">Delete</button>
                    </form> 
                    @else
                    <form action="{{ route('projects.restore', $project->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to restore this project?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-purple-600 px-3 py-1 text-white rounded">Restore</button>
                    </form> 
                   @endif  
         @endif
        @endauth          
         </div>           
            </div>
        @empty
            <div class="text-gray-500 text-center py-10">
                No projects found.
            </div>
        @endforelse
    </div>
    <!--Pagination-->
    <div class="mt-4">
    {{ $projects->links() }}
</div>

</div>
@endsection

