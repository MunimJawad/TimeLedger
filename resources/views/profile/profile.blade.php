@extends('layouts.app')

@section('title','Profile Page')

@section('content')

@if(session('success'))
    <script>
        alert(@json(session('success')));
    </script>
@endif

<div>
    <strong>Name: {{ $user->name }}</strong><br>
    <small>Email: {{ $user->email }}</small>
    <button><a href="{{ route('profile.edit') }}">Edit</a></button>
</div>
@endsection