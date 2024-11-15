<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John',
                'last_name' => 'Doe',
                'phone_number' => '123456789',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'admin',
            ],
            [
                'name' => 'Jane',
                'last_name' => 'Smith',
                'phone_number' => '987654321',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'admin',
            ],
            [
                'name' => 'Alice',
                'last_name' => 'Johnson',
                'phone_number' => '456789123',
                'email' => 'alice.johnson@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Bob',
                'last_name' => 'Brown',
                'phone_number' => '789123456',
                'email' => 'bob.brown@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Charlie',
                'last_name' => 'Davis',
                'phone_number' => '321654987',
                'email' => 'charlie.davis@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Emily',
                'last_name' => 'Wilson',
                'phone_number' => '654321789',
                'email' => 'emily.wilson@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'David',
                'last_name' => 'Miller',
                'phone_number' => '159753486',
                'email' => 'david.miller@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Sophia',
                'last_name' => 'Taylor',
                'phone_number' => '357159486',
                'email' => 'sophia.taylor@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Michael',
                'last_name' => 'Anderson',
                'phone_number' => '258147369',
                'email' => 'michael.anderson@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
            [
                'name' => 'Olivia',
                'last_name' => 'Thomas',
                'phone_number' => '741258963',
                'email' => 'olivia.thomas@example.com',
                'password' => Hash::make('Haslo12345@'),
                'role' => 'customer',
            ],
        ]);
    }
}
