<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaccinationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vaccinations')->insert([
            [
                'pet_ID' => 1,
                'vaccination_date' => '2023-01-15',
                'vaccine_type' => 'Rabies',
                'batch_number' => 'R12345',
            ],
            [
                'pet_ID' => 2,
                'vaccination_date' => '2023-02-20',
                'vaccine_type' => 'Feline Distemper',
                'batch_number' => 'F67890',
            ],
            [
                'pet_ID' => 3,
                'vaccination_date' => '2023-03-25',
                'vaccine_type' => 'Parvovirus',
                'batch_number' => 'P11223',
            ],
            [
                'pet_ID' => 4,
                'vaccination_date' => '2023-04-30',
                'vaccine_type' => 'Calicivirus',
                'batch_number' => 'C44556',
            ],
            [
                'pet_ID' => 5,
                'vaccination_date' => '2023-05-15',
                'vaccine_type' => 'Bordetella',
                'batch_number' => 'B77889',
            ],
            [
                'pet_ID' => 6,
                'vaccination_date' => '2023-06-20',
                'vaccine_type' => 'Leptospirosis',
                'batch_number' => 'L99001',
            ],
            [
                'pet_ID' => 7,
                'vaccination_date' => '2023-07-25',
                'vaccine_type' => 'Lyme Disease',
                'batch_number' => 'L22334',
            ],
            [
                'pet_ID' => 8,
                'vaccination_date' => '2023-08-30',
                'vaccine_type' => 'Canine Influenza',
                'batch_number' => 'C55667',
            ],
            [
                'pet_ID' => 9,
                'vaccination_date' => '2023-09-15',
                'vaccine_type' => 'Feline Leukemia',
                'batch_number' => 'F88900',
            ],
            [
                'pet_ID' => 10,
                'vaccination_date' => '2023-10-20',
                'vaccine_type' => 'Canine Hepatitis',
                'batch_number' => 'C12345',
            ],
            [
                'pet_ID' => 11,
                'vaccination_date' => '2023-11-25',
                'vaccine_type' => 'Panleukopenia',
                'batch_number' => 'P67890',
            ],
            [
                'pet_ID' => 12,
                'vaccination_date' => '2023-12-30',
                'vaccine_type' => 'Adenovirus',
                'batch_number' => 'A11223',
            ],
            [
                'pet_ID' => 13,
                'vaccination_date' => '2024-01-15',
                'vaccine_type' => 'Rabies',
                'batch_number' => 'R44556',
            ],
            [
                'pet_ID' => 14,
                'vaccination_date' => '2024-02-20',
                'vaccine_type' => 'Bordetella',
                'batch_number' => 'B77889',
            ],
            [
                'pet_ID' => 15,
                'vaccination_date' => '2024-03-25',
                'vaccine_type' => 'Feline Distemper',
                'batch_number' => 'F99001',
            ],
        ]);
    }
}
