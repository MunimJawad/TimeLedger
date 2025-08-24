@extends('layouts.app')
@section('title', 'Register')

@section('content')


@if ($errors->any())
   <div>
      @foreach ($errors->all() as $error )
        <p class="text-red-600">{{ $error }}</p>
      @endforeach
    </div>
@endif

<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
    <form action="{{ route('register') }}" method="POST">
      @csrf  
      <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Create an Account</h2>

      <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          id="username" 
          name="name" 
          type="text" 
          placeholder="Username"
        >
      </div>

      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          type="text" 
          name="email" 
          id="email" 
          placeholder="Your email"
        >
      </div>

      <div class="mb-4">
        <label for="password" class="block font-semibold text-gray-700 text-sm mb-2">Password</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          type="password" 
          id="password" 
          name="password" 
          placeholder="Create a password"
        >
      </div>

      <div class="mb-4">
        <label for="password_confirmation" class="block font-semibold text-gray-700 text-sm mb-2">Confirm Password</label>
        <input 
          class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300" 
          type="password" 
          id="password_confirmation" 
          name="password_confirmation" 
          placeholder="Confirm your password"
        >
      </div>
      
      <div class="mb-4">
        <input 
          type="submit" 
          value="Register" 
          class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-300"
        >
      </div>
    </form>

    <p class="text-center text-gray-500 text-sm mt-4">
      Already have an account? 
      <a href="{{ route('login.form') }}" class="text-blue-500 hover:underline">Login Here</a>
    </p>
  </div>
</div>

@endsection
