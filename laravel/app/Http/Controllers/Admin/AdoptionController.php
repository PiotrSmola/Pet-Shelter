<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adoption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdoptionController extends Controller
{
    public function index(Request $request)
    {
        $status = in_array($request->query('status'), Adoption::STATUSES, true) ? $request->query('status') : null;

        $adoptions = Adoption::with(['pet', 'user'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.adoptions', [
            'adoptions' => $adoptions,
            'status' => $status,
            'chart' => $this->monthlyChart(),
        ]);
    }

    public function updateStatus(Request $request, Adoption $adoption)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['completed', 'cancelled'])],
        ]);

        if (! $adoption->isActive()) {
            return response()->json(['message' => 'Only pending or reserved adoptions can be changed.'], 422);
        }

        $adoption->status = $data['status'];

        if ($data['status'] === 'completed') {
            $adoption->adoption_date = now()->toDateString();
        }

        $adoption->save();

        return response()->json([
            'success' => true,
            'status' => $adoption->status,
            'label' => $adoption->status_label,
        ]);
    }

    // Completed adoptions per month for the last 12 months (current month included).
    private function monthlyChart(): array
    {
        $start = now()->startOfMonth()->subMonths(11);

        $counts = Adoption::where('status', 'completed')
            ->where('adoption_date', '>=', $start->toDateString())
            ->get(['adoption_date'])
            ->countBy(fn (Adoption $adoption) => $adoption->adoption_date->format('Y-m'));

        $chart = ['labels' => [], 'data' => []];

        for ($i = 0; $i < 12; $i++) {
            $month = $start->copy()->addMonths($i);
            $chart['labels'][] = $month->format('M Y');
            $chart['data'][] = $counts[$month->format('Y-m')] ?? 0;
        }

        return $chart;
    }
}
