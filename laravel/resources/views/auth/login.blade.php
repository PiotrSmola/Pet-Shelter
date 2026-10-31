@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="container page page--narrow">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">Log in</h1>
                <p class="page-header__subtitle">Welcome back. Log in to adopt or reserve an animal.</p>
            </div>
        </header>

        <div class="panel">
            <form method="POST" action="{{ route('login.authenticate') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" autocomplete="email" maxlength="100" required autofocus
                        @error('email') aria-describedby="email-error" aria-invalid="true" @enderror>
                    @error('email')
                        <div class="invalid-feedback" id="email-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-field">
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            autocomplete="current-password" required
                            @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
                        <button type="button" class="password-toggle" data-password-toggle aria-controls="password" aria-label="Show password">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" @checked(old('remember'))>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Log in</button>
            </form>
        </div>

        <p class="text-center text-muted mt-4 mb-0">
            New here? <a href="{{ route('register') }}">Create an account</a>
        </p>
    </div>
@endsection
