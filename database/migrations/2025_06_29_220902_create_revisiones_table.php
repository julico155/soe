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
        Schema::create('revisiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->unsignedTinyInteger('p_score')->comment('Puntuación para P (0-4)');
            $table->unsignedTinyInteger('o_score')->comment('Puntuación para O (0-4)');
            $table->unsignedTinyInteger('s1_score')->comment('Puntuación para S1 (0-2)');
            $table->unsignedTinyInteger('total_score')->comment('Suma de P+O+S1 (0-10)'); // Almacenar el total
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisiones');
    }
};