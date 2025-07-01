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
        Schema::create('programas', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            $table->string('sigla', 20)->unique()->nullable(false); // VARCHAR(20) UNIQUE NOT NULL
            $table->string('tipo', 20); // VARCHAR(20)
            $table->string('titulo', 255)->nullable(false); // VARCHAR(255) NOT NULL
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};
