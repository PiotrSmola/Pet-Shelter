@extends('layouts.app')

@php
    $species = $filters['species'] ?? null;
    $weight = $filters['weight'] ?? null;
    $ageMin = $filters['age_min'] ?? null;
    $ageMax = $filters['age_max'] ?? null;
    $sort = $filters['sort'] ?? null;
    $hasFilters = $species || $weight || $ageMin !== null || $ageMax !== null || $sort;
    $total = $pets->total();
    $speciesTitle = ['dog' => 'Dogs', 'cat' => 'Cats'][$species] ?? 'Animals';
@endphp

@section('title', $species ? $speciesTitle . ' for adoption' : 'Animals for adoption')
@section('description', 'Dogs and cats from our shelter waiting for a new home. Filter by species, size and age.')

@section('content')
    <div class="container page">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">{{ $species ? $speciesTitle . ' waiting for a home' : 'Animals waiting for a home' }}</h1>
                <p class="page-header__subtitle">
                    @if ($total === 0)
                        No animals found
                    @else
                        {{ $total }} {{ $total === 1 ? 'animal is' : 'animals are' }} looking for a family{{ $hasFilters ? ' with these filters' : '' }}
                    @endif
                </p>
            </div>
        </header>

        <form class="filter-bar" method="get" action="{{ route('pets.index') }}" data-autosubmit aria-label="Filter animals">
            <div class="filter-bar__field">
                <span class="form-label" id="filter-species">Species</span>
                <div class="segmented" role="group" aria-labelledby="filter-species">
                    <input type="radio" class="btn-check" name="species" id="sp-all" value="" @checked(!$species)>
                    <label class="segmented__btn" for="sp-all">All</label>
                    <input type="radio" class="btn-check" name="species" id="sp-dog" value="dog" @checked($species === 'dog')>
                    <label class="segmented__btn" for="sp-dog">Dogs</label>
                    <input type="radio" class="btn-check" name="species" id="sp-cat" value="cat" @checked($species === 'cat')>
                    <label class="segmented__btn" for="sp-cat">Cats</label>
                </div>
            </div>

            <div class="filter-bar__field">
                <label class="form-label" for="filter-weight">Size</label>
                <select class="form-select form-select-sm" id="filter-weight" name="weight">
                    <option value="" @selected(!$weight)>Any size</option>
                    <option value="small" @selected($weight === 'small')>Small (up to 5 kg)</option>
                    <option value="medium" @selected($weight === 'medium')>Medium (5–15 kg)</option>
                    <option value="large" @selected($weight === 'large')>Large (over 15 kg)</option>
                </select>
            </div>

            <div class="filter-bar__field filter-bar__field--narrow">
                <label class="form-label" for="filter-age-min">Age from</label>
                <input type="number" class="form-control form-control-sm" id="filter-age-min" name="age_min"
                    min="0" max="30" step="1" inputmode="numeric" placeholder="0" value="{{ $ageMin }}">
            </div>

            <div class="filter-bar__field filter-bar__field--narrow">
                <label class="form-label" for="filter-age-max">Age to</label>
                <input type="number" class="form-control form-control-sm" id="filter-age-max" name="age_max"
                    min="0" max="30" step="1" inputmode="numeric" placeholder="Any" value="{{ $ageMax }}">
            </div>

            <div class="filter-bar__field">
                <label class="form-label" for="filter-sort">Sort by</label>
                <select class="form-select form-select-sm" id="filter-sort" name="sort">
                    <option value="" @selected(!$sort)>Default</option>
                    <option value="age_asc" @selected($sort === 'age_asc')>Youngest first</option>
                    <option value="age_desc" @selected($sort === 'age_desc')>Oldest first</option>
                </select>
            </div>

            <div class="filter-bar__actions">
                {{-- Selects and radios submit on change (data-autosubmit); the age fields submit on
                     Enter or when they lose focus. Without JS this button applies the filters. --}}
                <button type="submit" class="btn btn-primary btn-sm filter-bar__submit">Apply</button>
                @if ($hasFilters)
                    <a class="btn btn-link btn-sm" href="{{ route('pets.index') }}">Clear filters</a>
                @endif
            </div>
        </form>

        @if ($pets->isNotEmpty())
            <h2 class="visually-hidden">Results</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                @foreach ($pets as $pet)
                    <div class="col">
                        @include('layouts.partials.pet-card', ['pet' => $pet])
                    </div>
                @endforeach
            </div>

            {{ $pets->links('layouts.partials.pagination') }}
        @elseif ($hasFilters)
            <div class="empty-state">
                <i class="bi bi-search empty-state__icon" aria-hidden="true"></i>
                <h2 class="empty-state__title">No animals match these filters</h2>
                <p class="empty-state__text">Try a different size or age range, or look at everyone who is waiting.</p>
                <a class="btn btn-quiet" href="{{ route('pets.index') }}">Clear filters</a>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-house-heart empty-state__icon" aria-hidden="true"></i>
                <h2 class="empty-state__title">Everyone has found a home</h2>
                <p class="empty-state__text">There are no animals waiting right now. New ones arrive often, so check back soon.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.filter-bar');
            if (!form) return;
            var timer = null;

            // Age fields: apply shortly after the value is committed (blur, Enter, spinner clicks).
            form.querySelectorAll('input[type="number"]').forEach(function (input) {
                var initial = input.value;
                input.addEventListener('input', function () { window.clearTimeout(timer); });
                input.addEventListener('change', function () {
                    window.clearTimeout(timer);
                    if (input.value === initial || !input.checkValidity()) return;
                    timer = window.setTimeout(function () {
                        if (form.requestSubmit) form.requestSubmit(); else form.submit();
                    }, 600);
                });
            });

            // Keep the URL tidy: leave empty filters out of the query string.
            form.addEventListener('submit', function () {
                form.querySelectorAll('select, input').forEach(function (el) {
                    if (el.name && el.value === '' && (el.type !== 'radio' || el.checked)) el.disabled = true;
                });
            });
            window.addEventListener('pageshow', function () {
                form.querySelectorAll(':disabled').forEach(function (el) { el.disabled = false; });
            });
        });
    </script>
@endpush
