@extends('layouts.app')

@section('title', 'Regulations')
@section('description', 'Terms and conditions for registering, adopting and reserving animals at Pet Shelter.')

@section('content')
    <div class="container page">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">Regulations</h1>
                <p class="page-header__subtitle">Terms and conditions for using Pet Shelter.</p>
            </div>
        </header>

        <div class="prose measure">
            <ol class="terms">
                <li>
                    <h2>Registration</h2>
                    <ul>
                        <li>Any user wishing to use the services of Pet Shelter must register by providing their actual personal details, including their full name and e-mail address.</li>
                        <li>Users are required to update their personal information on the service if it changes.</li>
                    </ul>
                </li>
                <li>
                    <h2>Animal selection</h2>
                    <ul>
                        <li>Users have access to a wide selection of animals available for adoption or reservation at Pet Shelter.</li>
                        <li>The status and availability of each animal are clearly marked.</li>
                    </ul>
                </li>
                <li>
                    <h2>Adoption and reservation</h2>
                    <ul>
                        <li>Adopting or reserving an animal is possible by completing the necessary forms and agreeing to the terms and conditions.</li>
                        <li>After submitting the adoption or reservation request, the user receives confirmation along with details regarding the next steps and required documentation.</li>
                        <li>The maximum period for a reservation is 2 days (48 hours), after which the reservation expires if it has not been finalised.</li>
                    </ul>
                </li>
                <li>
                    <h2>Responsibility for animal welfare</h2>
                    <ul>
                        <li>Customers are responsible for the well-being and proper care of the animal from the moment of adoption or during the reservation period.</li>
                        <li>Any harm or neglect towards the animal during this period may result in legal action and additional charges.</li>
                    </ul>
                </li>
                <li>
                    <h2>Termination of agreement</h2>
                    <ul>
                        <li>Pet Shelter reserves the right to terminate the agreement in cases of breach of terms, including failure to provide adequate care for the animal.</li>
                    </ul>
                </li>
            </ol>

            <p class="text-muted mt-5 mb-0">Questions about these terms? Write to us at <a href="mailto:petshelter@example.com">petshelter@example.com</a>.</p>
        </div>
    </div>
@endsection
