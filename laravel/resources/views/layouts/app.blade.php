<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    @hasSection('title')
        <title>@yield('title') · Pet Shelter</title>
    @else
        <title>Pet Shelter</title>
    @endif
    @hasSection('description')
        <meta name="description" content="@yield('description')">
    @endif

    {{-- Apply the saved / system theme before first paint (no flash). --}}
    <script>
        (function () {
            var d = document.documentElement, t = null;
            d.classList.add('js');
            try { t = localStorage.getItem('ps-theme'); } catch (e) {}
            if (t !== 'light' && t !== 'dark') {
                t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            d.setAttribute('data-bs-theme', t);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500..600&family=Source+Sans+3:ital,wght@0,400..700;1,400&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @stack('styles')

    <script defer src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <a class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-primary" style="z-index: 1100" href="#main">Skip to content</a>

    @include('layouts.partials.navbar')
    @include('layouts.partials.flash')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
