<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    public function adopt($id)
    {
        $pet = Pet::findOrFail($id);

        if (Auth::check()) {
            Adoption::create([
                'pet_id' => $pet->id,
                'customer_id' => Auth::id(),
                'adoption_date' => now(),
                'status' => 'completed'
            ]);

            return redirect()->route('user.profile')->with('success', 'You have adopted a pet');
        }

        return redirect()->route('login');
    }

    public function reserve($id)
    {
        $pet = Pet::findOrFail($id);

        if (Auth::check()) {
            Adoption::create([
                'pet_id' => $pet->id,
                'customer_id' => Auth::id(),
                'adoption_date' => now(),
                'status' => 'reserved'
            ]);

            return redirect()->route('user.profile')->with('success', 'You have reserved a pet');
        }

        return redirect()->route('login');
    }
}
