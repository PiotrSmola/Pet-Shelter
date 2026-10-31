@extends('layouts.app')

@section('title', 'My profile')

@php
    $isAdmin = $user->isAdmin();
@endphp

@section('content')
    <div class="container page">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">Hi, {{ $user->name }}</h1>
                <p class="page-header__subtitle">
                    {{ $isAdmin ? 'You are signed in as an administrator.' : 'Your account details and your adoption requests.' }}
                </p>
            </div>
            @if ($isAdmin)
                <div class="page-header__actions">
                    <a class="btn btn-primary" href="{{ route('admin.adoptions') }}">Go to admin panel</a>
                </div>
            @endif
        </header>

        <section class="panel" aria-labelledby="account-title">
            <h2 class="panel__title" id="account-title">Your details</h2>
            <dl class="facts facts--3">
                <div><dt>First name</dt><dd>{{ $user->name }}</dd></div>
                <div><dt>Last name</dt><dd>{{ $user->last_name }}</dd></div>
                <div><dt>E-mail</dt><dd class="text-break">{{ $user->email }}</dd></div>
                <div><dt>Phone number</dt><dd>{{ $user->phone_number }}</dd></div>
                <div><dt>Account</dt><dd>{{ $isAdmin ? 'Administrator' : 'Customer' }} #{{ $user->id }}</dd></div>
                @if ($user->created_at)
                    <div><dt>Member since</dt><dd>{{ $user->created_at->format('j M Y') }}</dd></div>
                @endif
            </dl>
            <div class="form-actions justify-content-start">
                <a class="btn btn-primary" href="{{ route('user.edit') }}"><i class="bi bi-pencil me-1" aria-hidden="true"></i>Edit profile</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-quiet"><i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Log out</button>
                </form>
            </div>
        </section>

        @unless ($isAdmin)
            <section class="section pb-0" aria-labelledby="adoptions-title">
                <div class="section-head">
                    <h2 class="section-title" id="adoptions-title">Your adoptions and reservations</h2>
                    @if ($adoptions->isNotEmpty())
                        <a class="link-arrow" href="{{ route('pets.index') }}">Browse animals <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="success-note panel mb-3">
                        @include('layouts.partials.success-check')
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if ($adoptions->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-heart empty-state__icon" aria-hidden="true"></i>
                        <h3 class="empty-state__title">No adoptions yet</h3>
                        <p class="empty-state__text">When you send an adoption request or reserve an animal, it will show up here.</p>
                        <a class="btn btn-quiet" href="{{ route('pets.index') }}">Browse animals</a>
                    </div>
                @else
                    <div class="table-wrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Animal</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adoptions as $adoption)
                                    @php
                                        $pet = $adoption->pet;
                                        $date = $adoption->adoption_date ?? $adoption->created_at;
                                        $left = null;
                                        if ($adoption->status === 'reserved' && $adoption->expires_at) {
                                            $minutes = (int) now()->diffInMinutes($adoption->expires_at, false);
                                            $left = $minutes <= 0 ? 'expiring now'
                                                : ($minutes < 60 ? 'expires in ' . $minutes . ' min' : 'expires in ' . intdiv($minutes, 60) . ' h');
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($pet)
                                                <a class="adoption-pet" href="{{ route('pets.show', $pet) }}">
                                                    <img class="cell-thumb" src="{{ $pet->photo_url }}" alt="" loading="lazy">
                                                    <span>{{ $pet->name }}</span>
                                                </a>
                                            @else
                                                <span class="cell-muted">Animal no longer listed</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">{{ $date ? $date->format('j M Y') : '' }}</td>
                                        <td>
                                            <span class="status-dot" data-status="{{ $adoption->status }}">{{ $adoption->status_label }}</span>
                                            @if ($left)
                                                <span class="cell-muted d-block d-sm-inline ms-sm-1"
                                                    title="Until {{ $adoption->expires_at->format('j M Y, H:i') }}">· {{ $left }}</span>
                                            @endif
                                        </td>
                                        <td class="table-actions">
                                            @if ($adoption->isActive())
                                                <form method="POST" action="{{ route('adoptions.cancel', $adoption) }}"
                                                    data-confirm="{{ $adoption->status === 'reserved' ? 'Cancel this reservation' : 'Cancel this adoption request' }}{{ $pet ? ' for ' . $pet->name : '' }}?">
                                                    @csrf
                                                    <button type="submit" class="btn btn-quiet btn-sm">Cancel</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        @endunless
    </div>
@endsection
