@extends('layouts.app')

@section('title','User List')

@section('content')

<div class="flex items-center justify-between mb-4">
  <h1 class="font-bold text-2xl">User List</h1>
  <div class="w-full max-w-xl min-w-[200px]">
                    <form action="{{ route('admin.user_list') }}" method="GET">
          <div class="relative flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute w-5 h-5 top-2.5 left-2.5 text-slate-600">
              <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
            </svg>
         
            <input
            class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-10 pr-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
            placeholder="Search users here..." 
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
                                class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded">
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