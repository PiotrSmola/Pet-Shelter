<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PetsSeeder::class,
            UsersSeeder::class,
            VaccinationsSeeder::class,
            AdoptionsSeeder::class,
        ]);
    }
}
