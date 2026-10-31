<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'species' => $this->option($request, 'species', ['dog', 'cat']),
            'weight' => $this->option($request, 'weight', ['small', 'medium', 'large']),
            'age_min' => $this->age($request, 'age_min'),
            'age_max' => $this->age($request, 'age_max'),
            'sort' => $this->option($request, 'sort', ['age_asc', 'age_desc']),
        ];

        $query = Pet::available();

        if ($filters['species']) {
            $query->where('species', ucfirst($filters['species']));
        }

        match ($filters['weight']) {
            'small' => $query->where('weight', '<=', 5),
            'medium' => $query->where('weight', '>', 5)->where('weight', '<=', 15),
            'large' => $query->where('weight', '>', 15),
            default => null,
        };

        if ($filters['age_min'] !== null) {
            $query->where('age', '>=', $filters['age_min']);
        }

        if ($filters['age_max'] !== null) {
            $query->where('age', '<=', $filters['age_max']);
        }

        if ($filters['sort']) {
            $query->orderBy('age', $filters['sort'] === 'age_asc' ? 'asc' : 'desc');
        }

        $pets = $query->orderBy('id')->paginate(9)->withQueryString();

        return view('pets.index', compact('pets', 'filters'));
    }

    public function show(Request $request, Pet $pet)
    {
        $pet->load(['vaccinations' => fn ($query) => $query->orderByDesc('vaccination_date')->orderByDesc('id')]);

        $isAvailable = $pet->isAvailable();

        $myAdoption = $request->user()
            ? $pet->adoptions()
                ->where('customer_id', $request->user()->id)
                ->whereIn('status', Adoption::ACTIVE_STATUSES)
                ->latest('id')
                ->first()
            : null;

        return view('pets.show', compact('pet', 'isAvailable', 'myAdoption'));
    }

    private function option(Request $request, string $key, array $allowed): ?string
    {
        $value = $request->query($key);

        if (! is_string($value)) {
            return null;
        }

        $value = strtolower($value);

        return in_array($value, $allowed, true) ? $value : null;
    }

    private function age(Request $request, string $key): ?int
    {
        $value = filter_var($request->query($key), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 255]]);

        return $value === false ? null : $value;
    }
}
