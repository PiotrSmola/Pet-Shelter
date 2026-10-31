@php
    $species = request()->routeIs('pets.index') ? request('species') : null;
@endphp
<nav class="navbar navbar-expand-lg sticky-top site-nav" aria-label="Main">
    <div class="container">
        <a class="navbar-brand site-brand" href="{{ route('index') }}">Pet Shelter</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav"
            aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>

        <div class="collapse navbar-collapse" id="siteNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}"
                        @if (request()->routeIs('index')) aria-current="page" @endif
                        href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('pets.*') ? 'active' : '' }}" href="{{ route('pets.index') }}"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">Pets</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ request()->routeIs('pets.index') && !$species ? 'active' : '' }}" href="{{ route('pets.index') }}">All animals</a></li>
                        <li><a class="dropdown-item {{ $species === 'dog' ? 'active' : '' }}" href="{{ route('pets.index', ['species' => 'dog']) }}">Dogs</a></li>
                        <li><a class="dropdown-item {{ $species === 'cat' ? 'active' : '' }}" href="{{ route('pets.index', ['species' => 'cat']) }}">Cats</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                        @if (request()->routeIs('about')) aria-current="page" @endif
                        href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('regulations') ? 'active' : '' }}"
                        @if (request()->routeIs('regulations')) aria-current="page" @endif
                        href="{{ route('regulations') }}">Regulations</a>
                </li>
                @can('is-admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.adoptions') }}"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('admin.adoptions*') ? 'active' : '' }}" href="{{ route('admin.adoptions') }}"><i class="bi bi-clipboard-check" aria-hidden="true"></i>Adoptions</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('admin.pets*') ? 'active' : '' }}" href="{{ route('admin.pets') }}"><i class="bi bi-collection" aria-hidden="true"></i>Pets</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('admin.vaccinations*') ? 'active' : '' }}" href="{{ route('admin.vaccinations') }}"><i class="bi bi-shield-plus" aria-hidden="true"></i>Vaccinations</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}"><i class="bi bi-people" aria-hidden="true"></i>Users</a></li>
                        </ul>
                    </li>
                @endcan
            </ul>

            <ul class="navbar-nav nav-tools align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <button type="button" class="btn btn-icon theme-toggle" data-theme-toggle aria-label="Switch theme">
                        <i class="bi bi-moon icon-to-dark" aria-hidden="true"></i>
                        <i class="bi bi-sun icon-to-light" aria-hidden="true"></i>
                    </button>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.profile') }}"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
                            <span class="nav-user__name">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}"><i class="bi bi-person" aria-hidden="true"></i>My profile</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('user.edit') ? 'active' : '' }}" href="{{ route('user.edit') }}"><i class="bi bi-pencil" aria-hidden="true"></i>Edit profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right" aria-hidden="true"></i>Log out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Log in</a>
                    </li>
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <a class="btn btn-quiet btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
