@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Vaccinations'])

<head>
    <style>
        .container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-group {
            margin-top: 10px;
        }
    </style>
</head>

<body>
@include('layouts.navbar')

<div class="container">
    <h1>Vaccinations Management</h1>

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

    <form action="{{ route('admin.vaccinations.storeVaccination') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pet_id">Pet ID</label>
            <input type="number" class="form-control" id="pet_id" name="pet_id" required value="{{ old('pet_id', request('pet_id')) }}">
        </div>
        <div class="form-group">
            <label for="vaccination_date">Vaccination Date</label>
            <input type="date" class="form-control" id="vaccination_date" name="vaccination_date" required value="{{ old('vaccination_date') }}">
        </div>
        <div class="form-group">
            <label for="vaccine_type">Vaccine Type</label>
            <input type="text" class="form-control" id="vaccine_type" name="vaccine_type" required value="{{ old('vaccine_type') }}">
        </div>
        <div class="form-group">
            <label for="batch_number">Batch Number</label>
            <input type="text" class="form-control" id="batch_number" name="batch_number" required value="{{ old('batch_number') }}">
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-success">Add Vaccination</button>
        </div>
    </form>

    <h2 class="mt-5">All Vaccinations</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pet ID</th>
                <th>Vaccination Date</th>
                <th>Vaccine Type</th>
                <th>Batch Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vaccinations as $vaccination)
                <tr>
                    <td>{{ $vaccination->id }}</td>
                    <td>{{ $vaccination->pet_id }}</td>
                    <td>{{ $vaccination->vaccination_date }}</td>
                    <td>{{ $vaccination->vaccine_type }}</td>
                    <td>{{ $vaccination->batch_number }}</td>
                    <td>
                        <form action="{{ route('admin.vaccinations.destroyVaccination', $vaccination->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $vaccinations->links('pagination::bootstrap-4') }}
    </div>
</div>

@include('layouts.footer', ['fixedBottom' => false])
</body>
</html>
