<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand green-after" href="{{ route('index') }}">
            <b>Pet Shelter</b>
        </a>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link green-after" href="{{ url('/') }}">Home Page</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link green-after" href="#" id="speciesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Species <i class="bi bi-chevron-down"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="speciesDropdown">
                        <li><a class="dropdown-item green-after" href="{{ route('pets.cats') }}">Cats</a></li>
                        <li><a class="dropdown-item green-after" href="{{ route('pets.dogs') }}">Dogs</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link green-after" href="{{ route('pets.all') }}">All Pets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link green-after" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link green-after" href="{{ route('regulamin') }}">Regulations</a>
                </li>
                @can('is-admin')
                    <li class="nav-item">
                        <a class="nav-link green-after" href="{{ route('admin.adoptions') }}">Adoptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link green-after" href="{{ route('admin.users') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link green-after" href="{{ route('admin.pets') }}">Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link green-after" href="{{ route('admin.vaccinations') }}">Vaccinations</a>
                    </li>
                @endcan
            </ul>
            <ul class="navbar-nav">
                <li class="pr-5">
                    <button class="nav-link" id="theme-toggle">
                        <i class="bi bi-moon-stars" id="theme-icon"></i>
                    </button>
                </li>
            </ul>
        </div>
        <div class="dropdown" id="navbar-user">
            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('storage/icons/avatarLogIn.svg') }}" alt="avatar" style="height: 30px">
                @if (Auth::check())
                    <span class="ms-2" style="color: inherit; text-decoration: none;">
                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                @if (Auth::check())
                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Log Out</a></li>
                @else
                    <li><a class="dropdown-item" href="{{ route('login') }}">Log In</a></li>
                @endif
            </ul>
        </div>
        @include('layouts.success-toast')
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
