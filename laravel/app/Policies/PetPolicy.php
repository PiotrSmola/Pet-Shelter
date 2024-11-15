<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can adopt the pet.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function adopt(User $user, Pet $pet)
    {
        return $user->role !== 'admin';
    }
}
