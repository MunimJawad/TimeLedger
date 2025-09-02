@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">{{ $project->name }} Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white shadow rounded-lg p-5 text-center">
            <h5 class="text-gray-600">Total Tasks</h5>
            <h3 class="text-2xl font-bold">{{ $stats['totalTasks'] }}</h3>
        </div>
        <div class="bg-green-100 shadow rounded-lg p-5 text-center">
            <h5 class="text-gray-600">Completed</h5>
            <h3 class="text-2xl font-bold text-green-700">{{ $stats['completedTasks'] }}</h3>
        </div>
        <div class="bg-yellow-100 shadow rounded-lg p-5 text-center">
            <h5 class="text-gray-600">Pending</h5>
            <h3 class="text-2xl font-bold text-yellow-700">{{ $stats['pendingTasks'] }}</h3>
        </div>
        <div class="bg-blue-100 shadow rounded-lg p-5 text-center">
            <h5 class="text-gray-600">In Progress</h5>
            <h3 class="text-2xl font-bold text-blue-700">{{ $stats['inProgressTasks'] }}</h3>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-10">
        <h4 class="text-xl font-semibold mb-2">Project Progress</h4>
        <div class="w-full bg-gray-200 h-6 rounded-full overflow-hidden">
            <div class="h-6 text-white text-sm font-medium text-center leading-6 
                @if($stats['progress'] == 100) bg-green-500
                @elseif($stats['progress'] >= 50) bg-blue-500
                @else bg-yellow-400
                @endif"
                style="width: {{ $stats['progress'] }}%;">
                {{ $stats['progress'] }}%
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Task Status Pie Chart -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h5 class="text-lg font-semibold mb-4">Task Status Overview</h5>
            <canvas id="taskStatusChart" height="200"></canvas>
        </div>

        <!-- Tasks by Deadline -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h5 class="text-lg font-semibold mb-4">Tasks by Deadline (Month)</h5>
            <canvas id="tasksByMonthChart" height="200"></canvas>
        </div>
    </div>

    <!-- Tasks per Member -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h5 class="text-lg font-semibold mb-4">Tasks Per Member</h5>
        <canvas id="tasksPerMemberChart" height="120"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Task Status Pie Chart
    new Chart(document.getElementById('taskStatusChart'), {
        type: 'pie',
        data: {
            labels: ['Completed', 'Pending', 'In Progress'],
            datasets: [{
                data: [{{ $stats['completedTasks'] }}, {{ $stats['pendingTasks'] }}, {{ $stats['inProgressTasks'] }}],
                backgroundColor: ['#22c55e', '#facc15', '#3b82f6']
            }]
        }
    });

    // Tasks by Month Bar Chart
    new Chart(document.getElementById('tasksByMonthChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['tasksByMonth']->keys()) !!},
            datasets: [{
                label: 'Tasks Due',
                data: {!! json_encode($stats['tasksByMonth']->values()) !!},
                backgroundColor: '#6366f1'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Tasks per Member Bar Chart
    new Chart(document.getElementById('tasksPerMemberChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['tasksPerMember']->keys()->map(fn($id) => \App\Models\User::find($id)?->name ?? 'Unknown')) !!},
            datasets: [{
                label: 'Tasks Assigned',
                data: {!! json_encode($stats['tasksPerMember']->values()) !!},
                backgroundColor: '#10b981'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
