<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->date('vaccination_date')->default(now())->check('vaccination_date >= "2020-01-01" AND vaccination_date <= CURRENT_DATE');
            $table->string('vaccine_type', 50);
            $table->string('batch_number', 10);
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
