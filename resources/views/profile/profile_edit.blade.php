@extends('layouts.app')

@section('title','Profile Form')

@section('content')


@if ($errors->any())
   <div>
      @foreach ($errors->all() as $error )
        <p class="text-red-600">{{ $error }}</p>
      @endforeach
    </div>
@endif

<div class="min-h-screen flex items-center justify-center bg-gray-100 -mt-15">
  <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
    <form action="{{ route('profile.update') }}" method="POST">
      @csrf  
      @method('PUT')
      <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Update Profile</h2>

      <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          id="username" 
          name="name" 
          type="text"
          value="{{ $user->name }}" 
          placeholder="Change Your Name"
        >
      </div>

      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          type="text" 
          name="email" 
          id="email" 
          value="{{ $user->email }}"
          placeholder="Change Your email"
        >
      </div>

      <div class="mb-4">
        <label for="password" class="block font-semibold text-gray-700 text-sm mb-2">Password</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          type="password" 
          id="password" 
          name="password" 
          placeholder="Change password"
        >
      </div>

   
      
      <div class="mb-4">
        <input 
          type="submit" 
          value="Update" 
          class="w-full bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-300"
        >
      </div>
    </form>

  </div>
</div>

@endsection