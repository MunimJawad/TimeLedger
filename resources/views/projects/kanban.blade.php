@extends('layouts.app')

@section('title','Kanban View')

@section('content')

<div class=" pb-4 text-3xl">
    <p>{{ $project->name }}</p>
</div>
<div class="flex space-x-6">
    @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $key => $title)
        <div class="w-1/3 bg-gray-100 p-4 rounded-lg" data-status="{{ $key }}">
            <h2 class="font-bold text-lg mb-4">{{ $title }}</h2>
            <div class="space-y-3 min-h-[300px]" id="column-{{ $key }}">
                @foreach ($tasks->where('status', $key) as $task)
                    <div class="p-3 bg-white shadow rounded cursor-move" data-id="{{ $task->id }}">
                        <p class="font-semibold">{{ $task->title }}</p>
                        <p class="text-sm text-gray-500">Assigned: {{ $task->assignee->name ?? 'Unassigned' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>


<script>
document.querySelectorAll("[id^=column-]").forEach(column => {
    new Sortable(column, {
        group: 'kanban',
        animation: 150,
      onEnd: function (evt) {
    let taskId = evt.item.dataset.id;
    let newStatus = evt.from.dataset.status; // Change to `evt.from` to get the column's status.

    fetch(`/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: newStatus })
    });
}
    });
});


</script>
@endsection