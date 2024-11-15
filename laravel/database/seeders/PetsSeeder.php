<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pets')->insert([
            [
                'name' => 'Max',
                'species' => 'Dog',
                'breed' => 'Labrador',
                'age' => 3,
                'weight' => 24.5,
                'photo_path' => 'photos/max.jpg',
                'description' => 'Friendly and energetic Labrador.',
            ],
            [
                'name' => 'Bella',
                'species' => 'Cat',
                'breed' => 'Persian',
                'age' => 2,
                'weight' => 4.3,
                'photo_path' => 'photos/bella.jpg',
                'description' => 'Calm and fluffy Persian cat.',
            ],
            [
                'name' => 'Charlie',
                'species' => 'Dog',
                'breed' => 'Beagle',
                'age' => 5,
                'weight' => 10.2,
                'photo_path' => 'photos/charlie.jpg',
                'description' => 'Loyal and curious Beagle.',
            ],
            [
                'name' => 'Lucy',
                'species' => 'Cat',
                'breed' => 'Siamese',
                'age' => 1,
                'weight' => 3.8,
                'photo_path' => 'photos/lucy.jpg',
                'description' => 'Playful and talkative Siamese cat.',
            ],
            [
                'name' => 'Rocky',
                'species' => 'Dog',
                'breed' => 'Bulldog',
                'age' => 4,
                'weight' => 22.0,
                'photo_path' => 'photos/rocky.jpg',
                'description' => 'Strong and gentle Bulldog.',
            ],
            [
                'name' => 'Luna',
                'species' => 'Cat',
                'breed' => 'Maine Coon',
                'age' => 3,
                'weight' => 6.5,
                'photo_path' => 'photos/luna.jpg',
                'description' => 'Large and friendly Maine Coon.',
            ],
            [
                'name' => 'Buddy',
                'species' => 'Dog',
                'breed' => 'Golden Retriever',
                'age' => 2,
                'weight' => 30.1,
                'photo_path' => 'photos/buddy.jpg',
                'description' => 'Loving and obedient Golden Retriever.',
            ],
            [
                'name' => 'Milo',
                'species' => 'Cat',
                'breed' => 'British Shorthair',
                'age' => 4,
                'weight' => 5.7,
                'photo_path' => 'photos/milo.jpg',
                'description' => 'Calm and independent British Shorthair.',
            ],
            [
                'name' => 'Bailey',
                'species' => 'Dog',
                'breed' => 'Poodle',
                'age' => 1,
                'weight' => 7.3,
                'photo_path' => 'photos/bailey.jpg',
                'description' => 'Intelligent and active Poodle.',
            ],
            [
                'name' => 'Nala',
                'species' => 'Cat',
                'breed' => 'Ragdoll',
                'age' => 2,
                'weight' => 4.8,
                'photo_path' => 'photos/nala.jpg',
                'description' => 'Affectionate and calm Ragdoll.',
            ],
            [
                'name' => 'Daisy',
                'species' => 'Dog',
                'breed' => 'Dachshund',
                'age' => 3,
                'weight' => 6.1,
                'photo_path' => 'photos/daisy.jpg',
                'description' => 'Curious and lively Dachshund.',
            ],
            [
                'name' => 'Simba',
                'species' => 'Cat',
                'breed' => 'Bengal',
                'age' => 1,
                'weight' => 5.0,
                'photo_path' => 'photos/simba.jpg',
                'description' => 'Energetic and playful Bengal cat.',
            ],
            [
                'name' => 'Toby',
                'species' => 'Dog',
                'breed' => 'Shih Tzu',
                'age' => 2,
                'weight' => 5.5,
                'photo_path' => 'photos/toby.jpg',
                'description' => 'Friendly and affectionate Shih Tzu.',
            ],
            [
                'name' => 'Lily',
                'species' => 'Cat',
                'breed' => 'Russian Blue',
                'age' => 3,
                'weight' => 4.2,
                'photo_path' => 'photos/lily.jpg',
                'description' => 'Quiet and affectionate Russian Blue.',
            ],
            [
                'name' => 'Oscar',
                'species' => 'Dog',
                'breed' => 'Boxer',
                'age' => 4,
                'weight' => 25.3,
                'photo_path' => 'photos/oscar.jpg',
                'description' => 'Energetic and playful Boxer.',
            ],
        ]);
    }
}
