<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script defer src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('js/theme.js') }}"></script>
    <style>
        body {
            font-family: "Raleway", sans-serif;
        }
        .rotate-icon {
            transition: transform 0.3s ease;
        }

        .rotate-icon.open {
            transform: rotate(180deg);
        }
        .green-after:hover {
            color: rgba(var(--bs-success-rgb), var(--bs-bg-opacity)) !important;
        }
    </style>
</head>
