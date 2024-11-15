@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Admin Pets'])

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .list-group-item {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .user-info > span {
            margin-right: 15px;
            white-space: nowrap;
        }
        .button-group a {
            margin-right: 10px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .btn-secondary {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5" style="min-height: 81vh">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>All Pets</h1>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.addPet') }}" class="btn btn-secondary custom-btn">Add Pet</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Species</th>
                        <th>Breed</th>
                        <th>Weight</th>
                        <th>Age</th>
                        <th>Description</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pets as $pet)
                        <tr>
                            <td>{{ $pet->id }}</td>
                            <td>{{ $pet->name }}</td>
                            <td>{{ $pet->species }}</td>
                            <td>{{ $pet->breed }}</td>
                            <td>{{ $pet->weight }}</td>
                            <td>{{ $pet->age }}</td>
                            <td>{{ $pet->description }}</td>
                            <td><img src="{{ asset('storage/' . $pet->photo_path) }}" alt="{{ $pet->name }}" width="50"></td>
                            <td style="display: flex;">
                                <a href="{{ route('admin.editPet', ['id' => $pet->id]) }}" class="btn btn-secondary m-1">Edit</a>
                                <form action="{{ route('admin.deletePet', ['id' => $pet->id]) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger m-1" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</button>
                                </form>
                                <a href="{{ route('admin.vaccinations', ['pet_id' => $pet->id]) }}" class="btn btn-info m-1">Vaccinate</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $pets->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
</body>
</html>
