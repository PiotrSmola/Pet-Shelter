@extends('layouts.app')

@section('title', 'Create an account')

@php
    // Text field helper data: name => [label, type, autocomplete, extra attributes]
    $fields = [
        'name' => ['First name', 'text', 'given-name', 'maxlength="20"'],
        'last_name' => ['Last name', 'text', 'family-name', 'maxlength="30"'],
        'email' => ['E-mail', 'email', 'email', 'maxlength="100"'],
        'phone_number' => ['Phone number', 'tel', 'tel', 'maxlength="15" inputmode="numeric" pattern="[0-9]*"'],
    ];
@endphp

@section('content')
    <div class="container page page--narrow">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">Create an account</h1>
                <p class="page-header__subtitle">You'll need an account to adopt or reserve an animal.</p>
            </div>
        </header>

        <div class="panel">
            <form method="POST" action="{{ route('register.store') }}" novalidate>
                @csrf

                <div class="row g-3">
                    @foreach ($fields as $field => [$label, $type, $autocomplete, $extra])
                        <div class="{{ in_array($field, ['name', 'last_name'], true) ? 'col-sm-6' : 'col-12' }}">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}"
                                class="form-control @error($field) is-invalid @enderror"
                                value="{{ old($field) }}" autocomplete="{{ $autocomplete }}" {!! $extra !!} required
                                @if ($loop->first) autofocus @endif
                                @if ($field === 'phone_number') aria-describedby="phone-help{{ $errors->has($field) ? ' ' . $field . '-error' : '' }}"
                                @elseif ($errors->has($field)) aria-describedby="{{ $field }}-error" @endif
                                @error($field) aria-invalid="true" @enderror>
                            @if ($field === 'phone_number')
                                <div class="form-text" id="phone-help">Digits only, e.g. 123456789.</div>
                            @endif
                            @error($field)
                                <div class="invalid-feedback" id="{{ $field }}-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <p class="form-text mt-0 mb-2" id="password-help">At least 8 characters, with upper and lower case letters, a number and a symbol.</p>
                        <div class="password-field">
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password" minlength="8" required
                                aria-describedby="password-help{{ $errors->has('password') ? ' password-error' : '' }}"
                                @error('password') aria-invalid="true" @enderror>
                            <button type="button" class="password-toggle" data-password-toggle aria-controls="password" aria-label="Show password">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="password_confirmation" class="form-label">Repeat password</label>
                        <div class="password-field">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                                autocomplete="new-password" required>
                            <button type="button" class="password-toggle" data-password-toggle aria-controls="password_confirmation" aria-label="Show password">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="form-text mt-3 mb-0">
                    By creating an account you accept the <a href="{{ route('regulations') }}">shelter regulations</a>.
                </p>

                <button type="submit" class="btn btn-primary w-100 mt-4">Create account</button>
            </form>
        </div>

        <p class="text-center text-muted mt-4 mb-0">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </p>
    </div>
@endsection
