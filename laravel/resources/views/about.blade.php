@extends('layouts.app')

@section('title', 'About us')
@section('description', 'Pet Shelter rescues, cares for and rehomes abandoned and neglected dogs and cats. Learn how adoption works and how to reach us.')

@section('content')
    <div class="container page">
        <header class="page-header">
            <div>
                <h1 class="page-header__title">About us</h1>
                <p class="page-header__subtitle">Every animal deserves a chance at a happy, healthy life.</p>
            </div>
        </header>

        <div class="prose measure">
            <p class="lead">Welcome to Pet Shelter, where we are dedicated to providing loving homes for animals in need.</p>
            <p>Our mission is to rescue, rehabilitate and rehome pets that have been abandoned or neglected. We believe every animal deserves a chance to live a happy and healthy life.</p>
            <p>At Pet Shelter, we work tirelessly to ensure that every pet receives the care and attention they deserve. Our team of volunteers and professionals is committed to creating a safe and nurturing environment for all our animals, preparing them for their forever homes.</p>
        </div>

        <section class="section" id="how-it-works" aria-labelledby="how-title">
            <div class="section-head">
                <h2 class="section-title" id="how-title">How adoption works</h2>
            </div>
            <ol class="steps">
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">Browse</h3>
                    <p class="steps__text">Look through the <a href="{{ route('pets.index') }}">animals waiting for a home</a> and read about their character, health and vaccinations.</p>
                </li>
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">Request or reserve</h3>
                    <p class="steps__text">Send an adoption request, which our staff review, or reserve the animal for 48 hours while you make up your mind. You need a free account for both.</p>
                </li>
                <li class="steps__item" data-reveal>
                    <h3 class="steps__title">We confirm and you meet</h3>
                    <p class="steps__text">We get in touch and arrange a visit so you can meet before going home together. You can follow the status of your request in your profile.</p>
                </li>
            </ol>
            <p class="mt-4 mb-0 text-muted">Details are in our <a href="{{ route('regulations') }}">regulations</a>.</p>
        </section>

        <section class="section pb-0" aria-labelledby="contact-title">
            <div class="section-head">
                <h2 class="section-title" id="contact-title">Visit or contact us</h2>
            </div>
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="panel h-100">
                        <dl class="facts contact-facts">
                            <div><dt>Address</dt><dd>1234 Shelter St, Animal City, AC 56789</dd></div>
                            <div><dt>E-mail</dt><dd><a href="mailto:petshelter@example.com">petshelter@example.com</a></dd></div>
                            <div><dt>Phone</dt><dd><a href="tel:1234567890">123-456-7890</a></dd></div>
                            <div><dt>Second line</dt><dd><a href="tel:9876543210">987-654-3210</a></dd></div>
                            <div><dt>Mon–Fri</dt><dd>9:00–18:00</dd></div>
                            <div><dt>Sat–Sun</dt><dd>10:00–16:00</dd></div>
                        </dl>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="map-frame">
                        <iframe title="Map showing the shelter location"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d243647.26868736212!2d-74.00597319999999!3d40.7127753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDAlMjA0MycyNi40Ik4gNzQlMjAwMScyOC41Ilc!5e0!3m2!1sen!2sus!4v1614081953472!5m2!1sen!2sus"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
