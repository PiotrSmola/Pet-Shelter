@extends('layouts.app')

@section('title', 'Edit ' . $pet->name)

@section('content')
    <div class="container page">
        @include('admin._nav')

        <div class="admin-form">
            <header class="page-header">
                <div>
                    <h1 class="page-header__title">Edit {{ $pet->name }}</h1>
                    <p class="page-header__subtitle"><a href="{{ route('pets.show', $pet) }}">View public page</a></p>
                </div>
            </header>

            <form class="panel" method="POST" action="{{ route('admin.pets.update', $pet) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                @include('admin.pets._form', ['pet' => $pet])

                <div class="form-actions">
                    <a class="btn btn-quiet" href="{{ route('admin.pets') }}">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
