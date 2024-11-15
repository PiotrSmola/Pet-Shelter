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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('species', 20)->check('length(species) <= 20');
            $table->string('breed', 50)->check('length(breed) <= 50');
            $table->tinyInteger('age')->unsigned();
            $table->decimal('weight', 10, 1)->unsigned()->default(1.0)->check('weight > 0');
            $table->string('photo_path', 100);
            $table->string('description', 250);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

