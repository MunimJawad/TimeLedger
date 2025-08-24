@extends('layouts.app')

@section('title','User List')

@section('content')


<h1>User List</h1>

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


@endsection