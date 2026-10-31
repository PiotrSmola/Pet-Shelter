@extends('layouts.app')

@php
    $age = (int) $pet->age;
    $ageLabel = $age < 1 ? 'Under 1 year' : $age . ' ' . ($age === 1 ? 'year' : 'years');
    $weight = rtrim(rtrim(number_format((float) $pet->weight, 1, '.', ''), '0'), '.');
    $species = ucfirst(strtolower((string) $pet->species));
    $canAdopt = Auth::check() && !$myAdoption && Auth::user()->can('adopt', $pet);
    $formatDate = function ($value, $format = 'j M Y') {
        if (empty($value)) return '';
        try { return \Illuminate\Support\Carbon::parse($value)->format($format); } catch (\Throwable $e) { return (string) $value; }
    };
@endphp

@section('title', $pet->name . ' · ' . $species . ' for adoption')
@section('description', \Illuminate\Support\Str::limit(trim($pet->name . ', ' . $pet->breed . ', ' . strtolower($ageLabel) . '. ' . $pet->description), 155))

@section('content')
    <div class="container page">
        <a class="link-back" href="{{ route('pets.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> All animals</a>

        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-6">
                <div class="pet-photo">
                    @if ($pet->photo_path)
                        <img src="{{ $pet->photo_url }}" alt="{{ $pet->name }}, {{ strtolower($species) }} ({{ $pet->breed }})" fetchpriority="high">
                    @else
                        <i class="bi bi-image pet-photo__placeholder" aria-hidden="true"></i>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="pet-detail__name">{{ $pet->name }}</h1>
                <p class="pet-detail__meta">{{ $species }} · {{ $pet->breed }} · {{ $ageLabel }} · {{ $weight }} kg</p>

                @if ($pet->description)
                    <p class="pet-detail__description">{{ $pet->description }}</p>
                @endif

                <dl class="facts pet-detail__facts">
                    <div><dt>Species</dt><dd>{{ $species }}</dd></div>
                    <div><dt>Breed</dt><dd>{{ $pet->breed }}</dd></div>
                    <div><dt>Age</dt><dd>{{ $ageLabel }}</dd></div>
                    <div><dt>Weight</dt><dd>{{ $weight }} kg</dd></div>
                </dl>

                <div class="panel pet-detail__action">
                    @guest
                        <h2 class="panel__title">Would you like to meet {{ $pet->name }}?</h2>
                        <p class="text-muted mb-3">Log in or create a free account to send an adoption request or reserve {{ $pet->name }} for 48 hours.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-accent btn-lg" href="{{ route('login') }}">Log in to adopt</a>
                            <a class="btn btn-quiet btn-lg" href="{{ route('register') }}">Register</a>
                        </div>
                    @else
                        @if ($myAdoption)
                            <h2 class="panel__title">Your request for {{ $pet->name }}</h2>
                            <p class="mb-1">
                                <span class="status-dot" data-status="{{ $myAdoption->status }}">{{ $myAdoption->status_label }}</span>
                                @if ($myAdoption->status === 'reserved' && $myAdoption->expires_at)
                                    <span class="text-muted"> · held until {{ $myAdoption->expires_at->format('j M, H:i') }}</span>
                                @endif
                            </p>
                            <p class="text-muted mb-3">
                                @if ($myAdoption->status === 'reserved')
                                    {{ $pet->name }} is on hold for you. Visit us before the reservation ends to complete the adoption.
                                @else
                                    Our staff will review your request and get in touch to arrange a visit.
                                @endif
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('adoptions.cancel', $myAdoption) }}"
                                    data-confirm="{{ $myAdoption->status === 'reserved' ? 'Cancel your reservation for ' . $pet->name . '?' : 'Cancel your adoption request for ' . $pet->name . '?' }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        {{ $myAdoption->status === 'reserved' ? 'Cancel reservation' : 'Cancel request' }}
                                    </button>
                                </form>
                                <a class="btn btn-quiet" href="{{ route('user.profile') }}">My adoptions</a>
                            </div>
                        @elseif ($canAdopt)
                            <h2 class="panel__title">Give {{ $pet->name }} a home</h2>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-accent btn-lg" data-bs-toggle="modal" data-bs-target="#adoptModal">Adopt {{ $pet->name }}</button>
                                <button type="button" class="btn btn-quiet btn-lg" data-bs-toggle="modal" data-bs-target="#reserveModal">Reserve for 48 h</button>
                            </div>
                            <ul class="choice-notes">
                                <li><strong>Adopt</strong> sends an adoption request. Our staff review it and contact you.</li>
                                <li><strong>Reserve</strong> holds {{ $pet->name }} for you for 48 hours while you decide.</li>
                            </ul>
                        @elseif (!$isAvailable)
                            <p class="pet-detail__note"><i class="bi bi-info-circle" aria-hidden="true"></i> Not available — this animal already has a home or is reserved.</p>
                        @elseif (Auth::user()->isAdmin())
                            <p class="pet-detail__note"><i class="bi bi-info-circle" aria-hidden="true"></i> Admin accounts can't adopt or reserve animals.</p>
                        @endif
                    @endguest
                </div>
            </div>
        </div>

        <section class="section pb-0" aria-labelledby="vaccinations-title">
            <div class="section-head">
                <h2 class="section-title" id="vaccinations-title">Vaccinations</h2>
            </div>
            @if ($pet->vaccinations->isEmpty())
                <p class="text-muted">No vaccinations recorded yet. Our vet will add them after the next check-up.</p>
            @else
                <div class="table-wrap">
                    <table class="table table-compact">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Vaccine</th>
                                <th scope="col">Batch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pet->vaccinations as $vaccination)
                                <tr>
                                    <td class="text-nowrap">{{ $formatDate($vaccination->vaccination_date) }}</td>
                                    <td>{{ $vaccination->vaccine_type }}</td>
                                    <td class="cell-muted">{{ $vaccination->batch_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    @if ($canAdopt)
            <div class="modal fade" id="adoptModal" tabindex="-1" aria-labelledby="adoptModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" method="POST" action="{{ route('adopt', $pet) }}">
                        @csrf
                        <div class="modal-header">
                            <h2 class="modal-title" id="adoptModalLabel">Adopt {{ $pet->name }}?</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>We'll send your adoption request to our staff. They'll review it and contact you to arrange a visit.</p>
                            <p class="mb-0 text-muted">{{ $pet->name }} won't be shown to other visitors while your request is open. You can cancel it any time from your profile.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-quiet" data-bs-dismiss="modal">Not now</button>
                            <button type="submit" class="btn btn-accent">Send adoption request</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" method="POST" action="{{ route('reserve', $pet) }}">
                        @csrf
                        <div class="modal-header">
                            <h2 class="modal-title" id="reserveModalLabel">Reserve {{ $pet->name }} for 48 hours?</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $pet->name }} will be held for you for 48 hours. Visit us during that time to complete the adoption.</p>
                            <p class="mb-0 text-muted">If the reservation runs out, {{ $pet->name }} becomes available to others again.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-quiet" data-bs-dismiss="modal">Not now</button>
                            <button type="submit" class="btn btn-primary">Reserve for 48 h</button>
                        </div>
                    </form>
                </div>
            </div>
    @endif
@endsection
