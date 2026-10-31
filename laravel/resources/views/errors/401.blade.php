@extends('errors::minimal')

@section('title', 'Please log in')
@section('code', '401')
@section('message', 'You need to log in first')
@section('text', 'This page is only available to signed-in users. Log in to your account and try again.')
@section('actions')
    <a class="btn btn-primary" href="{{ url('/auth/login') }}">Log in</a>
    <a class="btn btn-quiet" href="{{ url('/') }}">Go to the home page</a>
@endsection
