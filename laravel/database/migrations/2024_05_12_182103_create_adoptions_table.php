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
        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('adoption_date')->default(now())->check('adoption_date >= "2020-01-01" AND adoption_date <= CURRENT_DATE');
            $table->enum('status', ['reserved', 'completed', 'cancelled']);
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('adoptions');
    }
};
