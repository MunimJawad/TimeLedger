@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')




<h1>Welcome, {{ Auth::user()->name }}</h1>


@endsection
