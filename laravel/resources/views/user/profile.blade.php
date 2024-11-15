@include('layouts.html')

@include('layouts.slider')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - User Profile'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5" style="min-height: 81vh">
        <h2 class="text-center mb-4">User Profile</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body p-5">
                        <h4 class="mb-4">User Data</h4>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Phone Number:</strong> {{ $user->phone_number }}</p>
                        <p><strong>{{ $user->role === 'admin' ? 'Admin ID' : 'Customer ID' }}:</strong> {{ $user->id }}</p>
    
                        <div class="btn-group mt-4" role="group">
                            <a href="{{ route('editData') }}" class="btn btn-secondary m-3 mt-0">Edit Password and Data</a>
                            <a href="{{ route('logout') }}" class="btn btn-danger m-3 mt-0">Log Out</a>
                        </div>
                    </div>
                </div>
    
                @if($user->role !== 'admin')
                <div class="card mt-4 mb-5">
                    <div class="card-body">
                        <h4>Your Adoptions and Reservations</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pet Name</th>
                                    <th>Date of Adoption/Reservation</th>
                                    <th>Species</th>
                                    <th>Breed</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adoptions as $adoption)
                                    <tr>
                                        <td>{{ $adoption->pet->name }}</td>
                                        <td>{{ $adoption->adoption_date }}</td>
                                        <td>{{ $adoption->pet->species }}</td>
                                        <td>{{ $adoption->pet->breed }}</td>
                                        <td>{{ $adoption->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @include('layouts.footer', ['fixedBottom' => false])

</body>
</html>
