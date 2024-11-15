@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Edit Pet'])

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 10px;
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

        <h1>Edit Pet</h1>

        <form action="{{ route('admin.updatePet', ['id' => $pet->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $pet->name }}" required>
            </div>
            <div class="form-group">
                <label for="species">Species</label>
                <input type="text" class="form-control" id="species" name="species" value="{{ $pet->species }}" required>
            </div>
            <div class="form-group">
                <label for="breed">Breed</label>
                <input type="text" class="form-control" id="breed" name="breed" value="{{ $pet->breed }}" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="{{ $pet->age }}" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight</label>
                <input type="number" step="0.1" class="form-control" id="weight" name="weight" value="{{ $pet->weight }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ $pet->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>
            <button type="submit" class="btn btn-secondary m-2 bg-success">Save</button>
            <a href="{{ route('admin.pets') }}" class="btn btn-secondary m-2 bg-danger">Cancel</a>
        </form>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
</body>
</html>
