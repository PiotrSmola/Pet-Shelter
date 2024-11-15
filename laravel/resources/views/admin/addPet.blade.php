@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Add Pet'])

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

        <h1>Add Pet</h1>

        <form action="{{ route('admin.storePet') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            <div class="form-group">
                <label for="species">Species</label>
                <input type="text" class="form-control" id="species" name="species" value="{{ old('species') }}"
                    required>
            </div>
            <div class="form-group">
                <label for="breed">Breed</label>
                <input type="text" class="form-control" id="breed" name="breed" value="{{ old('breed') }}"
                    required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}"
                    required>
            </div>
            <div class="form-group">
                <label for="weight">Weight</label>
                <input type="number" step="0.1" class="form-control" id="weight" name="weight"
                    value="{{ old('weight') }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" required>
            </div>
            <button type="submit" class="btn btn-secondary m-2 bg-success">Add</button>
            <a href="{{ route('admin.pets') }}" class="btn btn-secondary m-2 bg-danger">Cancel</a>
        </form>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
