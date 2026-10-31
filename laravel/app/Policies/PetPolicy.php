<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function adopt(User $user, Pet $pet): bool
    {
        return ! $user->isAdmin() && $pet->isAvailable();
    }
}
