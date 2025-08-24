@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')


@if(session('success'))
    <script>
        alert(@json(session('success')));
    </script>
@endif

<h1>Welcome, {{ Auth::user()->name }}</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
@endsection
