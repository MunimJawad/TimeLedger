@extends('layouts.app')

@section('title', 'Home')

@section('header', 'Welcome to TimeLedger')

@section('content')
<div >
    <h2 class="text-3xl font-bold mb-4">Hello, {{ auth()->user()->name ?? 'Guest' }}!</h2>
    <p class="text-gray-700 mb-6">This is your custom TimeLedger SaaS dashboard.</p>
    <a href="" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Projects</a>
</div>
@endsection
