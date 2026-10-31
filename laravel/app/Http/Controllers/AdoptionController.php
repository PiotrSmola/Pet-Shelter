<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdoptionController extends Controller
{
    public function adopt(Request $request, Pet $pet)
    {
        return $this->createRequest($request, $pet, ['status' => 'pending'],
            "Your adoption request for {$pet->name} has been sent. We will contact you soon.");
    }

    public function reserve(Request $request, Pet $pet)
    {
        $hours = Adoption::RESERVATION_HOURS;

        return $this->createRequest($request, $pet, ['status' => 'reserved', 'expires_at' => now()->addHours($hours)],
            "{$pet->name} is reserved for you for {$hours} hours.");
    }

    public function cancel(Request $request, Adoption $adoption)
    {
        abort_unless($adoption->customer_id === $request->user()->id, 403);

        if (! $adoption->isActive()) {
            return back()->with('error', 'This request can no longer be cancelled.');
        }

        $adoption->update(['status' => 'cancelled']);

        return back()->with('success', 'Your request has been cancelled.');
    }

    private function createRequest(Request $request, Pet $pet, array $attributes, string $message)
    {
        abort_if($request->user()->isAdmin(), 403);

        // Lock the pet row so two simultaneous requests cannot both see the pet as available.
        $created = DB::transaction(function () use ($request, $pet, $attributes) {
            Pet::whereKey($pet->id)->lockForUpdate()->first();

            if (Gate::denies('adopt', $pet)) {
                return false;
            }

            Adoption::create([
                'pet_id' => $pet->id,
                'customer_id' => $request->user()->id,
                'adoption_date' => now()->toDateString(),
                ...$attributes,
            ]);

            return true;
        });

        if (! $created) {
            return back()->with('error', "Sorry, {$pet->name} is no longer available.");
        }

        return redirect()->route('user.profile')->with('success', $message);
    }
}
