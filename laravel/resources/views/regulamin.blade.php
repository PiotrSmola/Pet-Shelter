@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'Pet Shelter - Regulations'])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
        li>ul>li {
            font-weight: lighter;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')
    
    <h1 class="text-white container mt-5">Terms and Conditions</h1>
    <ol class="text-danger2 fw-bold container mt-5">
        <li class="mb-4">Registration
            <ul>
                <li>Any user wishing to utilize the services of Pet Shelter must register by providing their actual personal details, including their full name, address, and email address.</li>
                <li>Users are required to update their personal information on the service in the event of any changes.</li>
            </ul>
        </li>

        <li class="mb-4">Animal Selection
            <ul>
                <li>Users have access to a wide selection of animals available for adoption or reservation at Pet Shelter.</li>
                <li>The status and availability of each animal are clearly marked.</li>
            </ul>
        </li>

        <li class="mb-4">Adoption and Reservation
            <ul>
                <li>Adopting or reserving an animal is possible by completing the necessary forms and agreeing to the terms and conditions.</li>
                <li>After submitting the adoption or reservation request, the user receives confirmation along with details regarding the next steps and required documentation.</li>
                <li>The maximum period for a reservation is 2 days, after which the reservation will expire if not finalized.</li>
            </ul>
        </li>

        <li class="mb-4">Responsibility for Animal Welfare
            <ul>
                <li>Customers are responsible for the well-being and proper care of the animal from the moment of adoption or during the reservation period.</li>
                <li>Any harm or neglect towards the animal during this period may result in legal action and additional charges.</li>
            </ul>
        </li>

        <li class="mb-4">Termination of Agreement
            <ul>
                <li>Pet Shelter reserves the right to terminate the agreement in cases of breach of terms, including failure to provide adequate care for the animal.</li>
            </ul>
        </li>
    </ol>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    @include('layouts.footer', ['fixedBottom' => false])
</body>
</html>
