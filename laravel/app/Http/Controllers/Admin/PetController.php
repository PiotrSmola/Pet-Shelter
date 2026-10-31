<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetRequest;
use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['adoptions' => fn ($q) => $q->whereIn('status', Adoption::BLOCKING_STATUSES)])
            ->orderBy('id')
            ->paginate(10);

        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        return view('admin.pets.create');
    }

    public function store(PetRequest $request)
    {
        $data = $request->safe()->except('photo');
        $data['photo_path'] = $request->file('photo')->store('photos', 'public');

        Pet::create($data);

        return redirect()->route('admin.pets')->with('success', 'Pet added successfully.');
    }

    public function edit(Pet $pet)
    {
        return view('admin.pets.edit', compact('pet'));
    }

    public function update(PetRequest $request, Pet $pet)
    {
        $data = $request->safe()->except('photo');
        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $pet->photo_path;
            $data['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        $pet->update($data);

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('admin.pets')->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        Storage::disk('public')->delete($pet->photo_path);

        return redirect()->route('admin.pets')->with('success', 'Pet deleted successfully.');
    }
}
