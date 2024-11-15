@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - All dogs'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Dogs</h2>
        <div class="row">
            @foreach ($dogs as $dog)
                <div class="col-md-4">
                    <div class="card mb-4 bg-primary">
                        <img class="card-img-top" src="{{ asset('storage/' . $dog->photo_path) }}" alt="{{ $dog->name }}">
                        <div class="card-body bg-warning">
                            <h5 class="card-title text-dark text-center">Name: {{ $dog->name }}</h5>
                        </div>
                        <ul class="list-group list-group-flush bg-primary text-center">
                            <li class="list-group-item bg-primary">Species: {{ $dog->species }}</li>
                            <li class="list-group-item bg-primary">Breed: {{ $dog->breed }}</li>
                            <li class="list-group-item bg-primary">Age: {{ $dog->age }}</li>
                        </ul>
                        <div class="card-body text-center">
                            <a href="{{ route('pets.show', ['id' => $dog->id]) }}" class="btn btn-primary bg-secondary">View Details</a>
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
