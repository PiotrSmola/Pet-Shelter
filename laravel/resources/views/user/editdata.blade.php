@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Account Settings'])
<head>
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .footer {
            background-color: #f8f9fa;
            text-align: center;
            position: relative;
            width: 100%;
            clear: both;
        }
        .custom-btn {
            background-color: gray;
            color: black;
            border: none;
        }
        .custom-btn:hover {
            background-color: darkred;
            color: white;
        }
        .container {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .full-height {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .text-large {
            font-size: 1.5em;
            text-align: center;
        }
    </style>
</head>

<body>
@include('layouts.navbar')

@if (Auth::check())
<div class="container mt-5 mb-5 marginbig">
    @include('layouts.session-error')

    <div class="row mt-4 mb-4 text-center">
        <div class="col-12">
            <img src="{{ asset('storage/icons/settings.svg') }}" alt="Logo" class="img-fluid" style="max-width: 150px; margin-bottom: 20px;">
            <h1>Account Settings</h1>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
    
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
    
                <form method="POST" action="{{ route('user.update') }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="form-control" value="{{ Auth::user()->last_name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input id="phone_number" name="phone_number" type="text" class="form-control" value="{{ Auth::user()->phone_number }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="text-center mb-3 mt-4">
                        <button type="submit" class="btn custom-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Change Password</h2>
                <form method="POST" action="{{ route('user.change_password') }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" name="current_password" type="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input id="new_password" name="new_password" type="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control" required>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn custom-btn">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@else
    <div class="full-height">
        <p class="text-large">Please log in to access account settings.</p>
    </div>
@endif

@include('layouts.footer', ['fixedBottom' => false])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const settingsForm = document.querySelector('.needs-validation');
        settingsForm.addEventListener('submit', function (event) {
            let valid = true;

            if (!valid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
</script>
</body>
</html>
