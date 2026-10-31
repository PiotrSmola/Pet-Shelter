<?php

namespace App\Providers;

use App\Models\Pet;
use App\Models\User;
use App\Policies\PetPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Pet::class, PetPolicy::class);

        Gate::define('is-admin', fn (User $user) => $user->isAdmin());

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers()->symbols());
    }
}
