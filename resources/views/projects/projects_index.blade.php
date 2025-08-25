@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    
<div class="container mx-auto px-4 py-4">

    {{-- Create Button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Project List</h1>
        
        <div class="w-full max-w-xl min-w-[200px]">
                    <form action="{{ route('projects.index') }}" method="GET">
          <div class="relative flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute w-5 h-5 top-2.5 left-2.5 text-slate-600">
              <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
            </svg>
         
            <input
            class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-10 pr-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
            placeholder="Search projects here..." 
           name="search" value="{{ request('search') }}" />
            
            <button
              class="rounded-md bg-slate-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2"
              type="submit"

            >
              Search
            </button> 
          </div>
          </form>
        </div>

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
        @if (auth()->user()?->role =='admin') 
               
                   <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 px-3 py-1 text-white rounded">Delete</button>
                    </form>

                    <a href="{{ route('projects.edit', $project->id) }}"  class="bg-emerald-600 px-3 py-1 text-white rounded"><button >Edit</button></a>
                    
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

