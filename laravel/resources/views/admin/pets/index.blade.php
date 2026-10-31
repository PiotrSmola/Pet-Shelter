@extends('layouts.app')

@section('title', 'Manage pets')

@php
    $availability = ['completed' => 'Adopted', 'reserved' => 'Reserved', 'pending' => 'Awaiting approval'];
@endphp

@section('content')
    <div class="container page">
        @include('admin._nav')

        <header class="page-header">
            <div>
                <h1 class="page-header__title">Pets</h1>
                <p class="page-header__subtitle">All animals in the shelter, including adopted ones.</p>
            </div>
            <div class="page-header__actions">
                <a class="btn btn-primary" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add pet</a>
            </div>
        </header>

        @if ($pets->isEmpty())
            <div class="empty-state">
                <i class="bi bi-collection empty-state__icon" aria-hidden="true"></i>
                <h2 class="empty-state__title">No pets yet</h2>
                <p class="empty-state__text">Add the first animal to make it visible on the site.</p>
                <a class="btn btn-quiet" href="{{ route('admin.pets.create') }}">Add pet</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Species</th>
                            <th scope="col">Breed</th>
                            <th scope="col" class="text-end">Age</th>
                            <th scope="col" class="text-end">Weight</th>
                            <th scope="col">Availability</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pets as $pet)
                            @php
                                $blocking = $pet->adoptions->first(fn ($a) => $a->status === 'completed')
                                    ?? $pet->adoptions->first(fn ($a) => $a->status === 'reserved')
                                    ?? $pet->adoptions->first();
                                $age = (int) $pet->age;
                                $weight = rtrim(rtrim(number_format((float) $pet->weight, 1, '.', ''), '0'), '.');
                            @endphp
                            <tr>
                                <td>
                                    <div class="cell-pet">
                                        <img class="cell-thumb" src="{{ $pet->photo_url }}" alt="" loading="lazy">
                                        <a href="{{ route('pets.show', $pet) }}">{{ $pet->name }}</a>
                                    </div>
                                </td>
                                <td>{{ $pet->species }}</td>
                                <td>{{ $pet->breed }}</td>
                                <td class="text-end text-nowrap">{{ $age < 1 ? '< 1' : $age }} {{ $age === 1 ? 'yr' : 'yrs' }}</td>
                                <td class="text-end text-nowrap">{{ $weight }} kg</td>
                                <td>
                                    @if ($blocking)
                                        <span class="cell-muted">{{ $availability[$blocking->status] ?? $blocking->status_label }}</span>
                                    @else
                                        Available
                                    @endif
                                </td>
                                <td class="table-actions">
                                    <a class="btn btn-quiet btn-sm" href="{{ route('admin.vaccinations', ['pet_id' => $pet->id]) }}">
                                        Vaccinations<span class="visually-hidden"> for {{ $pet->name }}</span>
                                    </a>
                                    <a class="btn btn-quiet btn-sm" href="{{ route('admin.pets.edit', $pet) }}">
                                        Edit<span class="visually-hidden"> {{ $pet->name }}</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.pets.destroy', $pet) }}"
                                        data-confirm="Delete {{ $pet->name }}? Its adoptions and vaccinations will be removed too.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Delete<span class="visually-hidden"> {{ $pet->name }}</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $pets->links('layouts.partials.pagination') }}
        @endif
    </div>
@endsection
