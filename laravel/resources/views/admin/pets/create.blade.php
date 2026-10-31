@extends('layouts.app')

@section('title', 'Add pet')

@section('content')
    <div class="container page">
        @include('admin._nav')

        <div class="admin-form">
            <header class="page-header">
                <div>
                    <h1 class="page-header__title">Add pet</h1>
                    <p class="page-header__subtitle">The pet appears on the site as available right away.</p>
                </div>
            </header>

            <form class="panel" method="POST" action="{{ route('admin.pets.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                @include('admin.pets._form')

                <div class="form-actions">
                    <a class="btn btn-quiet" href="{{ route('admin.pets') }}">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add pet</button>
                </div>
            </form>
        </div>
    </div>
@endsection
