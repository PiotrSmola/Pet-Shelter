@include('layouts.html')

@include('layouts.slider')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Home Page'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/slider.css') }}" />
</head>

<body>
    @include('layouts.navbar')

    <div id="carouselExampleCaptions" class="carousel slide custom-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active custom-carousel-item">
                <img src="storage/images/banner.webp" class="d-block w-100 custom-carousel-img" alt="img 1">
                <div class="carousel-caption custom-carousel-caption d-none d-md-block">
                    <div class="bannerText">
                        <h1 class="text-white">Welcome to our Pet Shelter</h1>
                        <p class="text-white">Visit us and adopt your animal friend</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item custom-carousel-item">
                <img src="storage/images/banner2.webp" class="d-block w-100 custom-carousel-img" alt="img 2">
                <div class="carousel-caption custom-carousel-caption d-none d-md-block">
                    <div class="bannerText">
                        <h1 class="text-white">Welcome to our Pet Shelter</h1>
                        <p class="text-white">Visit us and adopt your animal friend</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item custom-carousel-item">
                <img src="storage/images/banner3.webp" class="d-block w-100 custom-carousel-img" alt="img 3">
                <div class="carousel-caption custom-carousel-caption d-none d-md-block">
                    <div class="bannerText">
                        <h1 class="text-white">Welcome to our Pet Shelter</h1>
                        <p class="text-white">Visit us and adopt your animal friend</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Animals That Have Found a Home</h2>
        <div class="row">
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet1.webp') }}" class="img-fluid rounded" alt="Pet 1">
            </div>
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet2.webp') }}" class="img-fluid rounded" alt="Pet 2">
            </div>
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet3.webp') }}" class="img-fluid rounded" alt="Pet 3">
            </div>
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet4.webp') }}" class="img-fluid rounded" alt="Pet 4">
            </div>
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet5.webp') }}" class="img-fluid rounded" alt="Pet 5">
            </div>
            <div class="col-md-2">
                <img src="{{ asset('storage/images/pet6.webp') }}" class="img-fluid rounded" alt="Pet 6">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Pets</h2>
        <div class="row">
            @foreach ($availablePets as $pet)
                <div class="col-md-4">
                    <div class="card mb-4 bg-primary">
                        <img class="card-img-top" src="{{ asset('storage/' . $pet->photo_path) }}"
                            alt="{{ $pet->name }}">
                        <div class="card-body bg-warning">
                            <h5 class="card-title text-center text-dark">Name: {{ $pet->name }}</h5>
                        </div>
                        <ul class="list-group list-group-flush bg-primary text-center">
                            <li class="list-group-item bg-primary">Species: {{ $pet->species }}</li>
                            <li class="list-group-item bg-primary">Breed: {{ $pet->breed }}</li>
                            <li class="list-group-item bg-primary">Age: {{ $pet->age }}</li>
                        </ul>
                        <div class="card-body text-center">
                            <a href="{{ route('pets.show', ['id' => $pet->id]) }}"
                                class="btn btn-primary bg-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Adoption Status</h2>
        <p class="text-center">Below you can see the ratio of all animals that came to our shelter to those adopted</p>
        <div class="d-flex justify-content-center mb-5 text-white">
            <canvas id="adoptionChart" width="300" height="300"></canvas>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('adoptionChart').getContext('2d');
            const adoptionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Adopted', 'Available'],
                    datasets: [{
                        label: 'Adoption Status',
                        data: [{{ $adoptedPetsCount }}, {{ $totalPets - $adoptedPetsCount }}],
                        backgroundColor: ['#02ad07', '#023ead'],
                        hoverBackgroundColor: ['#02ad07', '#023ead']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: getComputedStyle(document.body).getPropertyValue('--bs-body-color')
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' pets';
                                }
                            }
                        }
                    }
                }
            });

            function updateChartColors(theme) {
                const color = theme === 'dark' ? 'white' : 'black';
                adoptionChart.options.plugins.legend.labels.color = color;
                adoptionChart.update();
            }

            function getCurrentTheme() {
                return document.body.getAttribute('data-bs-theme') || 'light';
            }

            updateChartColors(getCurrentTheme());

            const observer = new MutationObserver(() => {
                updateChartColors(getCurrentTheme());
            });

            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });
        });
    </script>
</body>

</html>
