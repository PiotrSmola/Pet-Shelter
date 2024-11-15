<?php

namespace App\Http\Controllers;
use App\Models\Pet;
use Illuminate\Http\Request;


class PetController extends Controller
{
    public function showDogs()
    {
        $dogs = Pet::where('species', 'dog')
                    ->whereDoesntHave('adoptions', function ($query) {
                        $query->whereIn('status', ['reserved', 'completed']);
                    })->get();
        return view('pets.dogs', compact('dogs'));
    }

    public function showCats()
    {
        $cats = Pet::where('species', 'cat')
                    ->whereDoesntHave('adoptions', function ($query) {
                        $query->whereIn('status', ['reserved', 'completed']);
                    })->get();
        return view('pets.cats', compact('cats'));
    }

    public function show($id)
{
    $pet = Pet::with('vaccinations')->findOrFail($id);
    return view('pets.show', compact('pet'));
}

public function showAll(Request $request)
{
    $query = Pet::query();

    if ($request->filled('weight')) {
        switch ($request->input('weight')) {
            case 'small':
                $query->where('weight', '<=', 5);
                break;
            case 'medium':
                $query->whereBetween('weight', [5.1, 15]);
                break;
            case 'large':
                $query->where('weight', '>', 15);
                break;
        }
    }

    if ($request->filled('age_min')) {
        $query->where('age', '>=', $request->input('age_min'));
    }

    if ($request->filled('age_max')) {
        $query->where('age', '<=', $request->input('age_max'));
    }

    if ($request->filled('sort')) {
        switch ($request->input('sort')) {
            case 'age_asc':
                $query->orderBy('age', 'asc');
                break;
            case 'age_desc':
                $query->orderBy('age', 'desc');
                break;
        }
    }

    $availablePets = $query->whereDoesntHave('adoptions', function ($query) {
        $query->whereIn('status', ['reserved', 'completed']);
    })->paginate(9);

    return view('pets.all', compact('availablePets'));
}

}
