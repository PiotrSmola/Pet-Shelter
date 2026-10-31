<?php

namespace App\Http\Controllers;

use App\Models\Pet;

class IndexController extends Controller
{
    public function index()
    {
        $featuredPets = Pet::available()->latest('id')->take(6)->get();

        $recentlyAdopted = Pet::whereHas('adoptions', fn ($query) => $query->where('status', 'completed'))
            ->withMax(['adoptions as adopted_at' => fn ($query) => $query->where('status', 'completed')], 'adoption_date')
            ->orderByDesc('adopted_at')
            ->take(6)
            ->get();

        $stats = [
            'total' => Pet::count(),
            'available' => Pet::available()->count(),
            'adopted' => Pet::whereHas('adoptions', fn ($query) => $query->where('status', 'completed'))->count(),
        ];

        return view('index', compact('featuredPets', 'recentlyAdopted', 'stats'));
    }

    public function regulations()
    {
        return view('regulations');
    }

    public function about()
    {
        return view('about');
    }
}
