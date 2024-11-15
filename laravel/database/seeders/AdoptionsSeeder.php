<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdoptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('adoptions')->insert([
            [
                'pet_ID' => 1,
                'customer_ID' => 3,
                'adoption_date' => '2023-08-20',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 2,
                'customer_ID' => 4,
                'adoption_date' => '2023-08-15',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 3,
                'customer_ID' => 5,
                'adoption_date' => '2023-09-10',
                'status' => 'reserved',
            ],
            [
                'pet_ID' => 4,
                'customer_ID' => 6,
                'adoption_date' => '2023-10-05',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 5,
                'customer_ID' => 7,
                'adoption_date' => '2023-12-01',
                'status' => 'cancelled',
            ],
            [
                'pet_ID' => 6,
                'customer_ID' => 8,
                'adoption_date' => '2024-01-20',
                'status' => 'cancelled',
            ],
            [
                'pet_ID' => 7,
                'customer_ID' => 9,
                'adoption_date' => '2024-03-15',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 8,
                'customer_ID' => 7,
                'adoption_date' => '2024-03-18',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 9,
                'customer_ID' => 4,
                'adoption_date' => '2023-05-05',
                'status' => 'completed',
            ],
            [
                'pet_ID' => 10,
                'customer_ID' => 6,
                'adoption_date' => '2023-05-06',
                'status' => 'reserved',
            ],
        ]);
    }
}
