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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            $table->string('sigla', 20)->unique()->nullable(false); // VARCHAR(20) UNIQUE NOT NULL
            $table->string('nombre', 255)->nullable(false); // VARCHAR(255) NOT NULL
            $table->decimal('porcentaje_avance', 5, 2)->default(0); // NUMERIC(5,2) DEFAULT 0
            $table->foreignId('programa_id')->constrained('programas')->onDelete('cascade'); // INT REFERENCES Programas(id) ON DELETE CASCADE
            $table->foreignId('docente_id')->nullable()->constrained('users')->onDelete('set null'); // INT REFERENCES Usuarios(id) ON DELETE SET NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
