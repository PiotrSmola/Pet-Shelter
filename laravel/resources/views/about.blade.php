@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - About Us'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h1 class="text-center mb-4">About Us</h1>
        <p>
            Welcome to Pet Shelter, where we are dedicated to providing loving homes for animals in need. Our mission is to rescue, rehabilitate, and rehome pets that have been abandoned or neglected. We believe every animal deserves a chance to live a happy and healthy life. At Pet Shelter, we work tirelessly to ensure that every pet receives the care and attention they deserve. Our team of volunteers and professionals are committed to creating a safe and nurturing environment for all our animals, preparing them for their forever homes.
        </p>
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d243647.26868736212!2d-74.00597319999999!3d40.7127753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDAlMjA0MycyNi40Ik4gNzQlMjAwMScyOC41Ilc!5e0!3m2!1sen!2sus!4v1614081953472!5m2!1sen!2sus" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
            <div class="col-md-6 ml-5 col-sm-12 pb-sm-10">
                <h2 class="mb-4">Contact Us</h2>
                <p style="font-size: 1.2rem;"><strong>Address:</strong> 1234 Shelter St, Animal City, AC 56789</p>
                <p style="font-size: 1.2rem;"><strong>Email:</strong> petshelter@example.com</p>
                <p style="font-size: 1.2rem;"><strong>Phone 1:</strong> 123-456-7890</p>
                <p style="font-size: 1.2rem;"><strong>Phone 2:</strong> 987-654-3210</p>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
   