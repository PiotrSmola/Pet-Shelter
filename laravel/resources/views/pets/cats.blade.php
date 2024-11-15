@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - All cats'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Cats</h2>
        <div class="row">
            @foreach ($cats as $cat)
                <div class="col-md-4">
                    <div class="card mb-4 bg-primary">
                        <img class="card-img-top" src="{{ asset('storage/' . $cat->photo_path) }}" alt="{{ $cat->name }}">
                        <div class="card-body bg-warning">
                            <h5 class="card-title text-dark text-center">Name: {{ $cat->name }}</h5>
                        </div>
                        <ul class="list-group list-group-flush bg-primary text-center">
                            <li class="list-group-item bg-primary">Species: {{ $cat->species }}</li>
                            <li class="list-group-item bg-primary">Breed: {{ $cat->breed }}</li>
                            <li class="list-group-item bg-primary">Age: {{ $cat->age }}</li>
                        </ul>
                        <div class="card-body text-center">
                            <a href="{{ route('pets.show', ['id' => $cat->id]) }}" class="btn btn-primary bg-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
