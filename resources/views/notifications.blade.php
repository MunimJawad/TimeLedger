

@extends('layouts.app')

@section('title','Notifications')

@section('content')
<div class="container mx-auto max-w-4xl px-4 py-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">🔔 Notifications</h2>

    @if ($notifications->count())
       <ul class="space-y-4">
    @foreach ($notifications as $notification)
        <li class="list-group-item bg-white p-5 rounded-lg shadow flex items-center justify-between hover:shadow-md transition
            {{ is_null($notification->read_at) ? 'font-bold' : 'text-gray-500' }}">
            
            <div>
                <p class="text-gray-800 text-sm md:text-base">
                    {{ $notification->data['message'] ?? 'Notification' }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    {{ $notification->created_at->format('M d, Y h:i A') }}
                </p>
            </div>

            @if (isset($notification->data['project_id']))
                <a href="{{ route('projects.show', $notification->data['project_id']) }}"
                   class="btn btn-sm btn-primary bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md transition">
                    View Project
                </a>
            @endif
        </li>
    @endforeach
</ul>

    @else
        <p class="text-center text-gray-500 text-lg mt-6">No notifications yet.</p>
    @endif
</div>

 <!--Pagination-->
<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endsection

