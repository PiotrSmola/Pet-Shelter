@extends('layouts.app')

@section('title', 'Vaccinations')

@php
    $petIdValue = (int) old('pet_id', $selectedPetId);
    $selectedPet = $selectedPetId ? $pets->firstWhere('id', $selectedPetId) : null;
    $today = now()->toDateString();
@endphp

@section('content')
    <div class="container page">
        @include('admin._nav')

        <header class="page-header">
            <div>
                <h1 class="page-header__title">Vaccinations</h1>
                <p class="page-header__subtitle">Record vaccinations; they are listed on each pet's page.</p>
            </div>
        </header>

        <div class="row g-4">
            <div class="col-lg-4">
                <form class="panel" method="POST" action="{{ route('admin.vaccinations.store') }}" novalidate>
                    @csrf
                    <h2 class="panel__title">Add vaccination</h2>

                    <div class="mb-3">
                        <label class="form-label" for="pet_id">Pet</label>
                        <select class="form-select @error('pet_id') is-invalid @enderror" id="pet_id" name="pet_id" required>
                            <option value="" disabled @selected(! $petIdValue)>Choose a pet…</option>
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}" @selected($petIdValue === $pet->id)>{{ $pet->name }} (#{{ $pet->id }})</option>
                            @endforeach
                        </select>
                        @error('pet_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="vaccination_date">Date</label>
                        <input type="date" class="form-control @error('vaccination_date') is-invalid @enderror"
                            id="vaccination_date" name="vaccination_date" value="{{ old('vaccination_date', $today) }}"
                            min="2020-01-01" max="{{ $today }}" required>
                        @error('vaccination_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="vaccine_type">Vaccine</label>
                        <input type="text" class="form-control @error('vaccine_type') is-invalid @enderror"
                            id="vaccine_type" name="vaccine_type" value="{{ old('vaccine_type') }}" maxlength="50"
                            placeholder="e.g. Rabies" required>
                        @error('vaccine_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="batch_number">Batch number</label>
                        <input type="text" class="form-control @error('batch_number') is-invalid @enderror"
                            id="batch_number" name="batch_number" value="{{ old('batch_number') }}" maxlength="10" required>
                        @error('batch_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add vaccination</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-8">
                @if ($selectedPetId)
                    <p class="admin-note">
                        Showing vaccinations for <strong>{{ $selectedPet?->name ?? 'pet #' . $selectedPetId }}</strong>.
                        <a href="{{ route('admin.vaccinations') }}">Show all</a>
                    </p>
                @endif

                @if ($vaccinations->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-shield-plus empty-state__icon" aria-hidden="true"></i>
                        <h2 class="empty-state__title">No vaccinations recorded</h2>
                        <p class="empty-state__text">Use the form to add the first one.</p>
                    </div>
                @else
                    <div class="table-wrap">
                        <table class="table table-hover">
                            <caption class="visually-hidden">Vaccinations, newest first</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Pet</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Vaccine</th>
                                    <th scope="col">Batch</th>
                                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaccinations as $vaccination)
                                    @php $date = \Illuminate\Support\Carbon::parse($vaccination->vaccination_date); @endphp
                                    <tr>
                                        <td>
                                            @if ($vaccination->pet)
                                                <a href="{{ route('pets.show', $vaccination->pet) }}">{{ $vaccination->pet->name }}</a>
                                            @else
                                                <span class="cell-muted">Deleted pet</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap"><time datetime="{{ $date->toDateString() }}">{{ $date->format('j M Y') }}</time></td>
                                        <td>{{ $vaccination->vaccine_type }}</td>
                                        <td class="cell-muted">{{ $vaccination->batch_number }}</td>
                                        <td class="table-actions">
                                            <form method="POST" action="{{ route('admin.vaccinations.destroy', $vaccination) }}"
                                                data-confirm="Delete this {{ $vaccination->vaccine_type }} vaccination?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-quiet btn-sm" aria-label="Delete {{ $vaccination->vaccine_type }} vaccination of {{ $vaccination->pet?->name }}">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $vaccinations->links('layouts.partials.pagination') }}
                @endif
            </div>
        </div>
    </div>
@endsection
