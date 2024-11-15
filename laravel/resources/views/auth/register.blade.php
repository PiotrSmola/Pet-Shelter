@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Registration'])
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
    @include('layouts.session-error')
    
    <div class="container mt-5 mb-5 marginbig">
        <div class="row mt-4 mb-4 text-center">
            <div class="col-12">
                <img src="{{ asset('storage/icons/register.svg') }}" alt="Logo" class="img-fluid" style="max-width: 130px; margin-bottom: 25px;">
                <h1>Register Now</h1>
            </div>
        </div>
    
        @include('layouts.validation-error')
    
        <div class="row d-flex justify-content-center">
            <div class="col-10 col-sm-10 col-md-6 col-lg-4">
                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">Phone number</label>
                        <input id="phone_number" name="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                    </div>
                    <div class="text-center mt-4 mb-4">
                        <button class="btn custom-btn" type="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@include('layouts.footer', ['fixedBottom' => false])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form.needs-validation');
        form.addEventListener('submit', function (event) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            const phoneNumber = document.getElementById('phone_number');
            let valid = true;

            if (password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Passwords do not match.');
                valid = false;
            } else {
                passwordConfirm.setCustomValidity('');
            }

            const phoneNumberPattern = /^[0-9]+$/;
            if (!phoneNumberPattern.test(phoneNumber.value)) {
                phoneNumber.setCustomValidity('Phone number can only contain digits.');
                valid = false;
            } else {
                phoneNumber.setCustomValidity('');
            }

            if (!valid) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
    
</body>
</html>
