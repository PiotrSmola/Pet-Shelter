<?php

namespace App\Http\Middleware;

use App\Models\Adoption;
use Closure;
use Illuminate\Http\Request;

// Lazy fallback for the scheduled adoptions:expire-reservations command,
// so expired holds are released even when no scheduler is running.
class ExpireReservations
{
    public function handle(Request $request, Closure $next)
    {
        Adoption::expireReservations();

        return $next($request);
    }
}
