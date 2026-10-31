@extends('errors::minimal')

@section('title', 'Back soon')
@section('code', '503')
@section('message', 'We\'ll be back shortly')
@section('text', 'The website is closed for a short while for maintenance. Please try again in a few minutes.')
@section('actions')
    <button type="button" class="btn btn-primary" onclick="location.reload()">Try again</button>
@endsection
