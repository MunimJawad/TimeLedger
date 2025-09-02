@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">📊 Project Dashboard & Analytics</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($stats as $stat)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">{{ $stat['project']->name }}</h3>
                <small>{{ $stat['project']->deadline }}</small>
                
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Total Tasks:</strong> {{ $stat['total_tasks'] }}</p>
                    <p><strong>Completed:</strong> {{ $stat['completed_tasks'] }}</p>
                    <p><strong>Pending:</strong> {{ $stat['pending_tasks'] }}</p>
                    <p><strong>Members:</strong> {{ $stat['members_count'] }}</p>
                </div>

                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div 
                            class="
                                h-4 
                                text-xs 
                                font-medium 
                                text-white 
                                text-center 
                                leading-4 
                                rounded-full
                                @if($stat['progress'] == 100) bg-green-500 
                                @elseif($stat['progress'] >= 50) bg-blue-500 
                                @else bg-yellow-400 
                                @endif
                            "
                            style="width: {{ $stat['progress'] }}%">
                            {{ $stat['progress'] }}%
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@endsection
