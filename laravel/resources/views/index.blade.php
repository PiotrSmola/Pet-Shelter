@extends('layouts.app')

@section('title', 'Adopt a dog or cat')
@section('description', 'Dogs and cats from our shelter are waiting for a home. Browse the animals, reserve one for 48 hours and come to meet them.')

@section('content')
    @php
        $inCare = max(0, (int) $stats['total'] - (int) $stats['adopted']);
        $adopted = (int) $stats['adopted'];
    @endphp

    <section class="hero">
        <div class="container">
            <div class="hero__grid">
                <div>
                    <h1 class="hero__title">Find a friend who's been waiting for you</h1>
                    <p class="hero__text">Our dogs and cats are vaccinated, vet-checked and ready to meet you. Look around, pick someone you like and come say hello.</p>
                    <div class="hero__actions">
                        <a class="btn btn-accent btn-lg" href="{{ route('pets.index') }}">Browse animals</a>
                        <a class="link-arrow" href="{{ route('about') }}#how-it-works">How adoption works <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    @if ($stats['total'] > 0)
                        <p class="hero__stats">
                            <strong data-count-to="{{ $inCare }}">{{ $inCare }}</strong> {{ $inCare === 1 ? 'animal' : 'animals' }} in our care
                            <span aria-hidden="true">&nbsp;·&nbsp;</span>
                            <strong data-count-to="{{ $adopted }}">{{ $adopted }}</strong> {{ $adopted === 1 ? 'has' : 'have' }} found a home
                        </p>
                    @endif
                </div>
                <div class="hero__media">
                    <img src="{{ asset('storage/images/banner.webp') }}" alt="Dogs, cats and rabbits sitting on the grass in front of the shelter" fetchpriority="high">
                </div>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="waiting-title">
        <div class="container">
            <div class="section-head">
                <h2 class="section-title" id="waiting-title">Waiting for a home</h2>
                <a class="link-arrow" href="{{ route('pets.index') }}">See all animals <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>

            @if ($featuredPets->isNotEmpty())
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                    @foreach ($featuredPets as $pet)
                        <div class="col">
                            @include('layouts.partials.pet-card', ['pet' => $pet])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-house-heart empty-state__icon" aria-hidden="true"></i>
                    <h3 class="empty-state__title">Everyone has found a home</h3>
                    <p class="empty-state__text">There are no animals waiting right now. New ones arrive often, so check back soon.</p>
                </div>
            @endif
        </div>
    </section>

    @if ($recentlyAdopted->isNotEmpty())
        <section class="section" aria-labelledby="adopted-title">
            <div class="container">
                <div class="section-head">
                    <h2 class="section-title" id="adopted-title">Recently adopted</h2>
                </div>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                    @foreach ($recentlyAdopted as $pet)
                        <div class="col">
                            @include('layouts.partials.pet-card', ['pet' => $pet, 'compact' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" aria-labelledby="how-title">
        <div class="container">
            <div class="section-head">
                <h2 class="section-title" id="how-title">How adoption works</h2>
            </div>
            <ol class="steps">
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">Browse</h3>
                    <p class="steps__text">Look through the animals waiting for a home and read about their character, health and vaccinations.</p>
                </li>
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">Request or reserve</h3>
                    <p class="steps__text">Send an adoption request, or reserve the animal for 48 hours while you make up your mind.</p>
                </li>
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">We confirm and you meet</h3>
                    <p class="steps__text">Our staff review your request, get in touch and arrange a visit so you can meet before going home together.</p>
                </li>
            </ol>
        </div>
    </section>
@endsection
