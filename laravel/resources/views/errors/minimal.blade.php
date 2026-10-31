{{--
    Shared layout for every error page (overrides Laravel's own errors::minimal, so the framework's
    fallback pages such as 419 / 429 get the same look).
    Deliberately standalone: no Auth, session, DB, @can or route() calls, so it still renders during
    maintenance (503) or when the database is down (500).
    Sections: title, code, message (heading), text (optional explanation), actions (optional buttons).
--}}
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noindex">
    <title>@yield('title') · Pet Shelter</title>
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
</head>
<body>
    <nav class="navbar sticky-top site-nav" aria-label="Main">
        <div class="container">
            <a class="navbar-brand site-brand" href="{{ url('/') }}">Pet Shelter</a>
        </div>
    </nav>

    <main id="main">
        <div class="container page error-page">
            <p class="error-page__code" aria-hidden="true">@yield('code')</p>
            <h1 class="error-page__title">
                <span class="visually-hidden">Error @yield('code'): </span>@yield('message')
            </h1>
            @hasSection('text')
                <p class="error-page__text">@yield('text')</p>
            @endif
            <div class="error-page__actions">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <a class="btn btn-primary" href="{{ url('/') }}">Go to the home page</a>
                    <button type="button" class="btn btn-quiet" onclick="history.length > 1 ? history.back() : (location.href = '{{ url('/') }}')">Go back</button>
                @endif
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="site-footer__bottom mt-0 pt-0 border-0">
                <span>&copy; {{ date('Y') }} Pet Shelter</span>
                <span><a href="mailto:petshelter@example.com">petshelter@example.com</a></span>
            </div>
        </div>
    </footer>
</body>
</html>
