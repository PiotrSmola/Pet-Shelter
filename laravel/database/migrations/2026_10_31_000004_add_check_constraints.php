<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Real CHECK constraints (the old ->check() calls were silently ignored).
// MySQL does not allow non-deterministic functions such as CURRENT_DATE in a CHECK,
// so "not in the future" rules stay in validation only.
return new class extends Migration
{
    private array $checks = [
        'pets' => ['pets_weight_positive' => 'weight > 0'],
        'users' => ['users_phone_number_digits' => "phone_number REGEXP '^[0-9]+$'"],
        'adoptions' => ['adoptions_adoption_date_min' => "adoption_date >= '2020-01-01'"],
        'vaccinations' => ['vaccinations_vaccination_date_min' => "vaccination_date >= '2020-01-01'"],
    ];

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->checks as $table => $checks) {
            foreach ($checks as $name => $expression) {
                DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$name` CHECK ($expression)");
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->checks as $table => $checks) {
            foreach (array_keys($checks) as $name) {
                DB::statement("ALTER TABLE `$table` DROP CHECK `$name`");
            }
        }
    }
};
