@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Login'])
<head>
    <style>
        .marginbig {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 75.5vh;
        }

        .custom-btn {
            background-color: rgb(1, 144, 172);
            color: black;
            border: none; 
            font-size: 1.3rem;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }
        .custom-btn:hover {
            background-color: rgb(2, 137, 11);
            color: white;
        }
    </style>
</head>
<body>
@include('layouts.navbar')

<div class="container mt-5 mb-5 marginbig">
    @include('layouts.session-error')

    <div class="row mt-4 mb-4 text-center">
        <div class="col-12">
            <img src="{{ asset('storage/icons/lock.svg') }}" alt="Logo" class="img-fluid" style="max-width: 170px; margin-bottom: 25px;">
            <h1>Log In</h1>
        </div>
    </div>

    @include('layouts.validation-error')

    <div class="row d-flex justify-content-center">
        <div class="col-10 col-sm-10 col-md-6 col-lg-4">
            <form method="POST" action="{{ route('login.authenticate') }}" id="loginForm" class="needs-validation" novalidate>
                @csrf
                <div class="form-group mb-4">
                    <label for="email" class="form-label">E-Mail</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">Remember me!</label>
                </div>
                
                <div class="text-center mt-4 mb-4">
                    <button class="btn custom-btn" type="submit">Log In</button>
                </div>
                <p>You do not have an account? &nbsp<a href="{{ route('register') }}">Register now</a></p>
            </form>
        </div>
    </div>
</div>

@include('layouts.footer', ['fixedBottom' => false])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function (event) {
            let valid = true;
            const email = document.getElementById('email');
            const password = document.getElementById('password');
        
            if (!email.value.includes('@') || !email.value.includes('.')) {
                valid = false;
                email.classList.add('is-invalid');
            } else {
                email.classList.remove('is-invalid');
            }
        
            if (password.value.length < 8) {
                valid = false;
                password.classList.add('is-invalid');
            } else {
                password.classList.remove('is-invalid');
            }
        
            if (!valid) {
                event.preventDefault(); 
            }
        });
    });
</script>
</body>
</html>