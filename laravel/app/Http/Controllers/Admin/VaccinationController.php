<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index(Request $request)
    {
        $selectedPetId = filter_var($request->query('pet_id'), FILTER_VALIDATE_INT) ?: null;

        $vaccinations = Vaccination::with('pet')
            ->when($selectedPetId, fn ($query) => $query->where('pet_id', $selectedPetId))
            ->orderByDesc('vaccination_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $pets = Pet::orderBy('name')->get(['id', 'name']);

        return view('admin.vaccinations', compact('vaccinations', 'pets', 'selectedPetId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'vaccination_date' => 'required|date|after_or_equal:2020-01-01|before_or_equal:today',
            'vaccine_type' => 'required|string|max:50',
            'batch_number' => 'required|string|max:10',
        ]);

        Vaccination::create($data);

        return redirect()->route('admin.vaccinations', ['pet_id' => $data['pet_id']])
            ->with('success', 'Vaccination added successfully.');
    }

    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();

        return back()->with('success', 'Vaccination deleted successfully.');
    }
}
