<?php

namespace App\Http\Controllers;

use App\Models\Pet;


class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $availablePets = Pet::whereDoesntHave('adoptions', function ($query) {
        $query->whereIn('status', ['reserved', 'completed']);
    })->take(6)->get();

    $totalPets = Pet::count();
    $adoptedPetsCount = Pet::whereHas('adoptions', function ($query) {
        $query->where('status', 'completed');
    })->count();

    return view('index', compact('availablePets', 'totalPets', 'adoptedPetsCount'));
}

    public function regulamin()
    {
        return view('regulamin'); 
    }

    public function aboutUs()
    {
        return view('about'); 
    }
}
