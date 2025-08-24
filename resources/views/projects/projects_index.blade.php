@extends('layouts.app')

@section('title', 'Projects')

@section('content')

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4" id="success-message">
        {{ session('success') }}
    </div>
@endif

<div class="container mx-auto px-4 py-6">

    {{-- Create Button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Project List</h1>
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
                <h2 class="text-lg font-semibold text-gray-800">{{ $project->name }}</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $project->description ?? 'No description provided.' }}</p>
                <div class="mt-3 text-sm text-gray-500 mb-2">
                    <span class="font-medium text-gray-700">Manager:</span> {{ $project->owner->name ?? 'N/A' }}
                </div>
                 @auth
        @if (auth()->user()?->role =='admin') 
                <div class="flex space-x-4">
                   <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 px-3 py-2 text-white rounded">Delete</button>
                    </form>

                    <a href="{{ route('projects.edit', $project->id) }}"  class="bg-emerald-600 px-3 py-2 text-white rounded"><button >Edit</button></a>
                    </div>
         @endif
        @endauth          
                    
            </div>
        @empty
            <div class="text-gray-500 text-center py-10">
                No projects found.
            </div>
        @endforelse
    </div>

</div>
@endsection

<script>
     setTimeout(() => {
            const el = document.getElementById('success-message');
            if (el) el.style.display = 'none';
        }, 3000); // 3 seconds

        
    </script>
</script>