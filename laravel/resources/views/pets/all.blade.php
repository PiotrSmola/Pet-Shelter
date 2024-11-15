@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - All pets'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h2 class="text-center mb-4">All Available Pets</h2>

        <form method="GET" action="{{ route('pets.all') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="weight">Weight</label>
                    <select class="form-select" id="weight" name="weight">
                        <option value="">All</option>
                        <option value="small" {{ request('weight') == 'small' ? 'selected' : '' }}>Small (<= 5kg)</option>
                        <option value="medium" {{ request('weight') == 'medium' ? 'selected' : '' }}>Medium (5.1kg - 15kg)</option>
                        <option value="large" {{ request('weight') == 'large' ? 'selected' : '' }}>Large (> 15kg)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="age_min">Age (Min)</label>
                    <input type="number" class="form-control" id="age_min" name="age_min" min="0" max="30" value="{{ request('age_min') }}">
                </div>
                <div class="col-md-4">
                    <label for="age_max">Age (Max)</label>
                    <input type="number" class="form-control" id="age_max" name="age_max" min="0" max="30" value="{{ request('age_max') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="sort">Sort by Age</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="">None</option>
                        <option value="age_asc" {{ request('sort') == 'age_asc' ? 'selected' : '' }}>Youngest First</option>
                        <option value="age_desc" {{ request('sort') == 'age_desc' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>

        <div class="row">
            @foreach ($availablePets as $pet)
                <div class="col-md-4">
                    <div class="card mb-4 bg-primary">
                        <img class="card-img-top" src="{{ asset('storage/' . $pet->photo_path) }}" alt="{{ $pet->name }}">
                        <div class="card-body bg-warning">
                            <h5 class="card-title text-center text-dark">Name: {{ $pet->name }}</h5>
                        </div>
                        <ul class="list-group list-group-flush bg-primary text-center">
                            <li class="list-group-item bg-primary">Species: {{ $pet->species }}</li>
                            <li class="list-group-item bg-primary">Breed: {{ $pet->breed }}</li>
                            <li class="list-group-item bg-primary">Age: {{ $pet->age }}</li>
                            <li class="list-group-item bg-primary">Weight: {{ $pet->weight }} kg</li>
                        </ul>
                        <div class="card-body text-center">
                            <a href="{{ route('pets.show', ['id' => $pet->id]) }}" class="btn btn-primary bg-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $availablePets->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>

    @include('layouts.footer', ['fixedBottom' => false])
</body>
</html>
