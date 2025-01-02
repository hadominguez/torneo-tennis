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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['M', 'F']);
            $table->integer('skill_level'); // 0 - 100
            $table->integer('strength')->nullable(); // Solo Masculino
            $table->integer('speed')->nullable(); // Solo Masculino
            $table->integer('reaction_time')->nullable(); // Solo Femenino
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
