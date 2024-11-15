@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - 404'])
<head>
    <style>
        html, body {
            height: 100%; 
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; 
        }

        .container {
            flex: 1; 
            display: flex;
            flex-direction: column; 
            justify-content: center;
        }

        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
@include('layouts.navbar')

<div class="container mt-5 mb-5">
    @if (session('error'))
        <div class="row d-flex justify-content-center">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif
    <div class="row mt-4 mb-4 text-center card p-5">
        <h1 class="display-1 fw-bold">404</h1>
        <h2 class="mb-4">
            @if (App::environment('local'))
                {{ $exception->getMessage() ?: 'Page not found' }}
            @else
                Page not found
            @endif
        </h2>
        <p>Sorry, the page you are looking for does not exist or has been moved. Try  <a href="{{ url('/') }}">eturning to the home page</a> or contact us if you need further assistance.</p>
    </div>

    @include('layouts.validation-error')
</div>

@include('layouts.footer', ['fixedBottom' => false])
<script>
    document.getElementById("navbar-user").remove();
</script>
</body>
</html>
