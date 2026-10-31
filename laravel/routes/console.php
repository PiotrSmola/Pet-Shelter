<?php

use App\Models\Adoption;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('adoptions:expire-reservations', function () {
    $count = Adoption::expireReservations();
    $this->info("Cancelled {$count} expired reservation(s).");
})->purpose('Cancel reservations whose 48 h hold has expired');

Schedule::command('adoptions:expire-reservations')->everyFifteenMinutes();
