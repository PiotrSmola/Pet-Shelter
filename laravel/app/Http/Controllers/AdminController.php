<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
use App\Models\Vaccination;

class AdminController extends Controller
{
        public function adoptions()
    {
        $adoptions = Adoption::with(['pet', 'user'])->paginate(5);

        $adoptionsByMonth = Adoption::selectRaw('COUNT(*) as count, MONTH(adoption_date) as month')
            ->groupBy('month')
            ->pluck('count', 'month');

        return view('admin.adoptions', compact('adoptions', 'adoptionsByMonth'));
    }

    public function updateStatus(Request $request, $id)
    {
        $adoption = Adoption::findOrFail($id);
        $adoption->status = $request->input('status');
        $adoption->save();

        return response()->json(['success' => true]);
    }

        public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ]);
    
        $hasAdoptions = Adoption::where('customer_id', $user->id)->exists();
    
        if ($request->has('admin') && $hasAdoptions) {
            return redirect()->route('admin.users')->withErrors(['admin' => 'Cannot assign admin role to a user with existing adoptions.']);
        }
    
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->role = $request->has('admin') ? 'admin' : 'customer';
        $user->save();
    
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

        public function pets()
    {
        $pets = Pet::paginate(10);
        return view('admin.pets', compact('pets'));
    }

    public function addPet()
    {
        return view('admin.addPet');
    }

    public function create()
    {
        return view('admin.addPet');
    }

    public function storePet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'species' => 'required|string|max:20',
            'breed' => 'required|string|max:50',
            'age' => 'required|integer',
            'weight' => 'required|numeric',
            'description' => 'required|string|max:250',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->getClientOriginalName();
            $photoPath = $request->file('photo')->storeAs('public/photos', $photoName);
        }

        $pet = new Pet();
        $pet->name = $request->name;
        $pet->species = $request->species;
        $pet->breed = $request->breed;
        $pet->age = $request->age;
        $pet->weight = $request->weight;
        $pet->description = $request->description;
        $pet->photo_path = 'photos/' . $photoName;
        $pet->save();

        return redirect()->route('admin.pets')->with('success', 'Pet added successfully');
    }

    public function editPet($id)
    {
        $pet = Pet::findOrFail($id);
        return view('admin.editPet', compact('pet'));
    }

    public function updatePet(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:20',
            'species' => 'required|string|max:20',
            'breed' => 'required|string|max:50',
            'age' => 'required|integer',
            'weight' => 'required|numeric',
            'description' => 'required|string|max:250',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->storeAs(
                'photos', $request->name . '.' . $request->file('photo')->extension()
            );
            $pet->photo_path = $path;
        }

        $pet->update([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.pets')->with('success', 'Pet updated successfully.');
    }

    public function deletePet($id)
    {
        $pet = Pet::findOrFail($id);
        Storage::delete($pet->photo_path);
        $pet->delete();

        return redirect()->route('admin.pets')->with('success', 'Pet deleted successfully.');
    }
        public function createVaccination(Request $request)
    {
        $pet_id = $request->query('pet_id');
        $vaccinations = Vaccination::paginate(10);
        return view('admin.vaccinations', compact('pet_id', 'vaccinations'));
    }

    public function showVaccinations()
    {
        $vaccinations = Vaccination::paginate(10);
        return view('admin.vaccinations', compact('vaccinations'));
    }

    public function storeVaccination(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'vaccination_date' => 'required|date|after_or_equal:2020-01-01|before_or_equal:today',
            'vaccine_type' => 'required|string|max:50',
            'batch_number' => 'required|string|max:10',
        ]);

        Vaccination::create($request->all());

        return redirect()->route('admin.vaccinations')->with('success', 'Vaccination added successfully!');
    }

    public function destroyVaccination($id)
    {
        $vaccination = Vaccination::findOrFail($id);
        $vaccination->delete();

        return redirect()->route('admin.vaccinations')->with('success', 'Vaccination deleted successfully!');
    }
}

