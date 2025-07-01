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
        Schema::table('revisiones', function (Blueprint $table) {
            // Añadir una columna de texto para las observaciones, puede ser nula
            $table->text('observacion')->nullable()->after('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            $table->dropColumn('observacion');
        });
    }
};