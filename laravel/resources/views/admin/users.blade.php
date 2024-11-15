@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'Pet Shelter - Admin Users'])

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
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
        .btn-secondary{
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

        <h1>All Users</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td style="display: flex;">
                                <a href="#" class="btn btn-secondary" onclick="toggleEditPanel('{{ $user->id }}')">Edit</a>
                                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="edit-panel-{{ $user->id }}" style="display: none;">
                            <td colspan="6">
                                <form action="{{ route('admin.updateUser', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="form-group">
                                        <label for="name-{{ $user->id }}">Name</label>
                                        <input type="text" class="form-control" id="name-{{ $user->id }}" name="name" required value="{{ $user->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name-{{ $user->id }}">Last Name</label>
                                        <input type="text" class="form-control" id="last_name-{{ $user->id }}" name="last_name" required value="{{ $user->last_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email-{{ $user->id }}">Email</label>
                                        <input type="email" class="form-control" id="email-{{ $user->id }}" name="email" required value="{{ $user->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number-{{ $user->id }}">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number-{{ $user->id }}" name="phone_number" required value="{{ $user->phone_number }}">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="admin-{{ $user->id }}" name="admin" @if($user->role == 'admin') checked @endif>
                                        <label class="form-check-label" for="admin-{{ $user->id }}">Administrator</label>
                                    </div>
                                    <button type="submit" class="btn btn-secondary m-2 w-30">Save</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
    <script>
        function toggleEditPanel(userId) {
            var editPanel = document.getElementById('edit-panel-' + userId);
            if (editPanel.style.display === 'none') {
                editPanel.style.display = 'table-row';
            } else {
                editPanel.style.display = 'none';
            }
        }
    </script>
</body>
</html>
