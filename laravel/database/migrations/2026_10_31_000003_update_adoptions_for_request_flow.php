<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoptions', function (Blueprint $table) {
            // Redefining the column also drops the default baked in by the old ->default(now()).
            $table->date('adoption_date')->change();
            $table->enum('status', ['pending', 'reserved', 'completed', 'cancelled'])->default('pending')->change();
            $table->dateTime('expires_at')->nullable()->after('status');
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index(['pet_id', 'status']);
        });

        Schema::table('vaccinations', function (Blueprint $table) {
            $table->date('vaccination_date')->change();
        });
    }

    public function down(): void
    {
        DB::table('adoptions')->where('status', 'pending')->update(['status' => 'cancelled']);

        Schema::table('adoptions', function (Blueprint $table) {
            // MySQL replaced the foreign key's own index by (pet_id, status); recreate it first.
            $table->index('pet_id', 'adoptions_pet_id_foreign');
            $table->dropIndex(['status', 'expires_at']);
            $table->dropIndex(['pet_id', 'status']);
            $table->dropColumn(['expires_at', 'created_at', 'updated_at']);
            $table->enum('status', ['reserved', 'completed', 'cancelled'])->change();
        });
    }
};
