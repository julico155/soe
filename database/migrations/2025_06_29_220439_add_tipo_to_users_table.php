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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono', 20)->nullable()->after('email'); // Añadir teléfono
            $table->string('direccion', 255)->nullable()->after('telefono'); // Añadir dirección
            $table->string('tipo', 20)->after('direccion')->nullable(); // Añadir tipo con valor por defecto
            // Nota: La restricción CHECK ('tipo' IN ('administrativo', 'docente')) se manejará en la validación de Laravel.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefono', 'direccion', 'tipo']);
        });
    }
};
