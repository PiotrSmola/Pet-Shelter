@extends('layouts.app')

@section('title', 'Edit profile')

@php
    // name => [label, type, autocomplete, extra attributes]
    $fields = [
        'name' => ['First name', 'text', 'given-name', 'maxlength="20"'],
        'last_name' => ['Last name', 'text', 'family-name', 'maxlength="30"'],
        'email' => ['E-mail', 'email', 'email', 'maxlength="100"'],
        'phone_number' => ['Phone number', 'tel', 'tel', 'maxlength="15" inputmode="numeric" pattern="[0-9]*"'],
    ];
    $passwords = [
        'current_password' => ['Current password', 'current-password'],
        'new_password' => ['New password', 'new-password'],
        'new_password_confirmation' => ['Repeat new password', 'new-password'],
    ];
@endphp

@section('content')
    <div class="container page page--narrow">
        <a class="link-back" href="{{ route('user.profile') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> My profile</a>

        <header class="page-header">
            <div>
                <h1 class="page-header__title">Edit profile</h1>
                <p class="page-header__subtitle">Keep your contact details up to date so we can reach you about your adoptions.</p>
            </div>
        </header>

        <section class="panel" aria-labelledby="details-title">
            <h2 class="panel__title" id="details-title">Personal details</h2>
            <form method="POST" action="{{ route('user.update') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @foreach ($fields as $field => [$label, $type, $autocomplete, $extra])
                        <div class="{{ in_array($field, ['name', 'last_name'], true) ? 'col-sm-6' : 'col-12' }}">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}"
                                class="form-control @error($field) is-invalid @enderror"
                                value="{{ old($field, $user->{$field}) }}" autocomplete="{{ $autocomplete }}" {!! $extra !!} required
                                @error($field) aria-describedby="{{ $field }}-error" aria-invalid="true" @enderror>
                            @error($field)
                                <div class="invalid-feedback" id="{{ $field }}-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="form-actions">
                    <a class="btn btn-quiet" href="{{ route('user.profile') }}">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </section>

        <section class="panel" aria-labelledby="password-title">
            <h2 class="panel__title" id="password-title">Change password</h2>
            <p class="form-text mt-0 mb-3" id="new-password-help">The new password needs at least 8 characters, with upper and lower case letters, a number and a symbol.</p>
            <form method="POST" action="{{ route('user.change_password') }}" novalidate>
                @csrf
                @method('PUT')

                @foreach ($passwords as $field => [$label, $autocomplete])
                    <div class="{{ $loop->last ? '' : 'mb-3' }}">
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <div class="password-field">
                            <input id="{{ $field }}" name="{{ $field }}" type="password"
                                class="form-control @error($field) is-invalid @enderror"
                                autocomplete="{{ $autocomplete }}" required
                                @if ($field === 'new_password') aria-describedby="new-password-help{{ $errors->has($field) ? ' ' . $field . '-error' : '' }}"
                                @elseif ($errors->has($field)) aria-describedby="{{ $field }}-error" @endif
                                @error($field) aria-invalid="true" @enderror>
                            <button type="button" class="password-toggle" data-password-toggle aria-controls="{{ $field }}" aria-label="Show password">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error($field)
                            <div class="invalid-feedback d-block" id="{{ $field }}-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
            </form>
        </section>
    </div>
@endsection
