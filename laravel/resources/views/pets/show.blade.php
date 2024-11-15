@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Pet Details'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ $pet->name }} Details</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4 bg-primary">
                    <img class="card-img-top" src="{{ asset('storage/' . $pet->photo_path) }}" alt="{{ $pet->name }}">
                    <div class="card-body bg-warning">
                        <h5 class="card-title text-dark text-center">Name: {{ $pet->name }}</h5>
                    </div>
                    <ul class="list-group list-group-flush bg-primary text-center">
                        <li class="list-group-item bg-primary">Species: {{ $pet->species }}</li>
                        <li class="list-group-item bg-primary">Breed: {{ $pet->breed }}</li>
                        <li class="list-group-item bg-primary">Age: {{ $pet->age }}</li>
                        <li class="list-group-item bg-primary">Weight: {{ $pet->weight }} kg</li>
                        <li class="list-group-item bg-primary">Description: {{ $pet->description }}</li>
                    </ul>
                    <div class="login-btn" style="width: 100%">
                        @if (Auth::check())
                            @can('adopt', $pet)
                                <button type="button" style="width: 100%" class="btn btn-block btn-lg btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#adoptModal">
                                    <b>Adopt "{{ $pet->name }}"</b>
                                </button>
                                <button type="button" style="width: 100%"
                                    class="btn btn-block btn-lg btn-secondary mt-2" data-bs-toggle="modal"
                                    data-bs-target="#reserveModal">
                                    <b>Reserve "{{ $pet->name }}"</b>
                                </button>
                            @else
                                <button type="button" style="width: 100%" class="btn btn-block btn-lg btn-primary" disabled>
                                    <b>Adopt "{{ $pet->name }}"</b>
                                </button>
                                <button type="button" style="width: 100%" class="btn btn-block btn-lg btn-secondary" disabled>
                                    <b>Reserve "{{ $pet->name }}"</b>
                                </button>
                            @endcan
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg btn-block" style="width: 100%"><b>Log in to adopt "{{ $pet->name }}"</b></a>
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-lg btn-block" style="width: 100%"><b>Log in to reserve "{{ $pet->name }}"</b></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">Vaccination History</h5>
                    </div>
                    <div class="card-body">
                        @if($pet->vaccinations->isEmpty())
                            <p class="text-center">No vaccination records available.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($pet->vaccinations as $vaccination)
                                    <li class="list-group-item">
                                        <strong>Date:</strong> {{ $vaccination->vaccination_date }}<br>
                                        <strong>Vaccine Type:</strong> {{ $vaccination->vaccine_type }}<br>
                                        <strong>Batch Number:</strong> {{ $vaccination->batch_number }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])

    <!-- Adopt Modal -->
    <div class="modal fade" id="adoptModal" tabindex="-1" aria-labelledby="adoptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adoptModalLabel">Adoption Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to adopt "{{ $pet->name }}"? You will be responsible for this pet.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('adopt', ['id' => $pet->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reserve Modal -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">Reservation Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reserve "{{ $pet->name }}"? You will have 48 hours to adopt this pet.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('reserve', ['id' => $pet->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
