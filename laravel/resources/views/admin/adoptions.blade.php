@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Admin Adoptions'])

<head>
    <style>
        html,
        body {
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
        }

        .footer {
            margin-top: auto;
        }

        .chart-container {
            position: relative;
            margin: auto;
            width: 50vw;
        }

        .status-completed {
            background-color: #28a745 !important;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545 !important;
            color: white;
        }

        .status-reserved {
            background-color: #ffc107 !important;
            color: black;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-4">
        <h1>All Adoptions</h1>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Pet ID</th>
                        <th>Pet Name</th>
                        <th>Species</th>
                        <th>Breed</th>
                        <th>Customer Email</th>
                        <th>Adoption Date</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adoptions as $adoption)
                        <tr>
                            <td><img src="{{ asset('storage/' . $adoption->pet->photo_path) }}" alt="Pet Image"
                                    class="img-fluid" style="width: 100px;"></td>
                            <td>{{ $adoption->pet->id }}</td>
                            <td>{{ $adoption->pet->name }}</td>
                            <td>{{ $adoption->pet->species }}</td>
                            <td>{{ $adoption->pet->breed }}</td>
                            <td>{{ $adoption->user->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($adoption->adoption_date)->format('Y-m-d') }}</td>
                            <td class="text-center status-{{ $adoption->status }}">
                                @if ($adoption->status === 'completed')
                                    <span>{{ $adoption->status }}</span>
                                @else
                                    <select class="form-select status-dropdown status-{{ $adoption->status }}" data-id="{{ $adoption->id }}">
                                        <option value="cancelled"
                                            {{ $adoption->status === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                        <option value="reserved"
                                            {{ $adoption->status === 'reserved' ? 'selected' : '' }}>reserved</option>
                                        <option value="completed"
                                            {{ $adoption->status === 'completed' ? 'selected' : '' }}>completed
                                        </option>
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $adoptions->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="container mt-4 mb-4">
            <h1>Adoptions by Month</h1>
            <div class="chart-container mb-4 ">
                <canvas id="adoptionsChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adoptionsByMonth = @json($adoptionsByMonth);
            const ctx = document.getElementById('adoptionsChart').getContext('2d');
            const adoptionsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(adoptionsByMonth),
                    datasets: [{
                        label: 'Number of Adoptions',
                        data: Object.values(adoptionsByMonth),
                        backgroundColor: 'rgb(95, 92, 192)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: getComputedStyle(document.body).getPropertyValue('--bs-body-color')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 10,
                            ticks: {
                                stepSize: 1,
                                color: getComputedStyle(document.body).getPropertyValue('--bs-body-color')
                            }
                        },
                        x: {
                            ticks: {
                                color: getComputedStyle(document.body).getPropertyValue('--bs-body-color')
                            }
                        }
                    }
                }
            });

            function updateChartColors(theme) {
                const color = theme === 'dark' ? 'white' : 'black';
                adoptionsChart.options.plugins.legend.labels.color = color;
                adoptionsChart.options.scales.x.ticks.color = color;
                adoptionsChart.options.scales.y.ticks.color = color;
                adoptionsChart.update();
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

            document.querySelectorAll('.status-dropdown').forEach(dropdown => {
                dropdown.addEventListener('change', function() {
                    const adoptionId = this.getAttribute('data-id');
                    const status = this.value;

                    fetch(`/admin/adoptions/${adoptionId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Status updated successfully.');
                                if (status === 'completed') {
                                    const td = this.parentElement;
                                    td.innerHTML = '<span class="status-completed">' + status + '</span>';
                                } else {
                                    this.parentElement.className = 'status-' + status;
                                }
                            } else {
                                alert('Failed to update status.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to update status.');
                        });
                });
            });
        });
    </script>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
