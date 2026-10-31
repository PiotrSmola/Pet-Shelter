<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdoptionsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // [pet_id, customer_id, status, requested at, decided at (completed / cancelled)]
        $adoptions = [
            [9, 4, 'completed', $now->copy()->subMonths(30), $now->copy()->subMonths(30)->addDays(3)],
            [7, 9, 'completed', $now->copy()->subMonths(8)->subDays(5), $now->copy()->subMonths(8)],
            [4, 6, 'completed', $now->copy()->subMonths(5)->subDays(4), $now->copy()->subMonths(5)],
            [5, 7, 'cancelled', $now->copy()->subMonths(4), $now->copy()->subMonths(4)->addDays(2)],
            [2, 4, 'completed', $now->copy()->subMonths(3)->subDays(2), $now->copy()->subMonths(3)],
            [1, 3, 'completed', $now->copy()->subMonth()->subDays(3), $now->copy()->subMonth()],
            [6, 8, 'cancelled', $now->copy()->subDays(20), $now->copy()->subDays(18)],
            [8, 7, 'completed', $now->copy()->subDays(6), $now->copy()->subDays(2)],
            [12, 5, 'cancelled', $now->copy()->subDays(5), $now->copy()->subDays(3)],
            [3, 5, 'pending', $now->copy()->subDays(2), null],
            [11, 3, 'pending', $now->copy()->subHours(5), null],
            [10, 6, 'reserved', $now->copy()->subHours(10), null],
        ];

        DB::table('adoptions')->insert(array_map(fn ($row) => [
            'pet_id' => $row[0],
            'customer_id' => $row[1],
            'status' => $row[2],
            'adoption_date' => ($row[2] === 'completed' ? $row[4] : $row[3])->toDateString(),
            'expires_at' => $row[2] === 'reserved' ? $row[3]->copy()->addHours(48) : null,
            'created_at' => $row[3],
            'updated_at' => $row[4] ?? $row[3],
        ], $adoptions));
    }
}
