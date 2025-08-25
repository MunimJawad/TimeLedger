@extends('layouts.app')
@section('title', 'Project Detail')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 space-y-4">
        <h1 class="text-2xl font-bold text-gray-800">{{ $project->name }}</h1>

        <div class="text-sm text-gray-500">
            <span class="font-medium text-gray-700">Owner:</span> {{ $project->owner->name }} |
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
@endsection
