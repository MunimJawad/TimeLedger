@extends('layouts.app')

@section('title','User List')

@section('content')

<div class="flex  flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
  <!-- Title -->
  <h1 class="font-bold text-2xl text-gray-800">User List <span class="text-blue-600">({{ $count }})</span></h1>

  <!-- Search & Filter Form -->
  <div class="w-full sm:max-w-xl">
    <form action="{{ route('admin.user_list') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-3">
      
      <!-- Search Box -->
      <div class="relative w-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
        </svg>

        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Search users..."
          class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 text-gray-700 transition shadow-sm"
          oninput="this.form.submit()"   />
      </div>

      <!-- Status Filter -->
      <div class="flex items-center gap-2">
        <label for="status" class="text-sm text-gray-600 font-medium">Status:</label>
        <select
          name="status"
          id="status"
          onchange="this.form.submit()"
          class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >
          <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
        </select>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
      >
        Search
      </button>
    </form>
  </div>
</div>


<table class="table-fixed w-full text-sm text-left text-gray-700 border border-gray-300">
    <thead class="bg-gray-100 uppercase text-xs text-gray-600">
        <tr>
            <th class="w-1/4 px-4 py-2 border">Name</th>
            <th class="w-1/4 px-4 py-2 border">Email</th>
            <th class="w-1/4 px-4 py-2 border">Role</th>
            <th class="w-1/4 px-4 py-2 border text-center">Action</th>
        </tr>
    </thead>

    <tbody class="bg-white">
        @foreach ($users as $user)
            <tr class="hover:bg-gray-500 hover:text-amber-50">
                <td class="px-4 py-2 border">{{ $user->name }}</td>
                <td class="px-4 py-2 border">{{ $user->email }}</td>
                <td class="px-4 py-2 border capitalize">{{ $user->role }}</td>

                <td class="px-4 py-2 border text-center">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded">
                        Edit
                    </a>

                    @if (!$user->deleted_at)                  
                    <span class="mx-1">|</span>
                    <form action="{{ route('admin.users.destroy',$user) }}" method="POST" class="inline-block" 
                    onsubmit="return confirm('Are you confirm to delete the user');">
                       @csrf
                       @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded">
                            Delete
                        </button>
                    </form>

                    @else
                    
                    <span class="mx-1">|</span>
                     <form action="{{ route('admin.users.restore',$user) }}" method="POST" class="inline-block" 
                    onsubmit="return confirm('Are you confirm to restore the user');">
                       @csrf
                       @method('PATCH')
                        <button type="submit" 
                                class="bg-purple-500 hover:bg-purple-700 text-white text-xs font-semibold px-3 py-1 rounded">
                            Restore
                        </button>
                    </form>
                     @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>

<!--Pagination-->
    <div class="mt-4">
    {{ $users->links() }}
</div>


@endsection