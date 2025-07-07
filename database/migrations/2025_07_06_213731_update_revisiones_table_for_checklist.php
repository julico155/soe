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
            // 1. Eliminar todas las columnas antiguas y las que podrían causar conflicto si existen
            // Esto asegura un estado limpio antes de añadir las nuevas columnas en el orden deseado.
            if (Schema::hasColumn('revisiones', 'p_score')) {
                $table->dropColumn(['p_score', 'o_score', 's1_score', 'total_score']);
            }
            if (Schema::hasColumn('revisiones', 'criterio1_cumplido')) { // Si existían los 3 criterios anteriores
                $table->dropColumn(['criterio1_cumplido', 'criterio2_cumplido', 'criterio3_cumplido']);
            }
            if (Schema::hasColumn('revisiones', 'teacher_response')) { // Si existía este campo
                $table->dropColumn('teacher_response');
            }
            if (Schema::hasColumn('revisiones', 'observacion')) { // Si existía este campo
                $table->dropColumn('observacion');
            }
            if (Schema::hasColumn('revisiones', 'justificacion')) { // Si existía este campo
                $table->dropColumn('justificacion');
            }

            // 2. Añadir los 10 nuevos campos de checklist en el orden deseado
            // Categoría 1 (4 criterios)
            $table->boolean('criterio1_1_cumplido')->default(false)->after('modulo_id');
            $table->boolean('criterio1_2_cumplido')->default(false)->after('criterio1_1_cumplido');
            $table->boolean('criterio1_3_cumplido')->default(false)->after('criterio1_2_cumplido');
            $table->boolean('criterio1_4_cumplido')->default(false)->after('criterio1_3_cumplido');

            // Categoría 2 (4 criterios)
            $table->boolean('criterio2_1_cumplido')->default(false)->after('criterio1_4_cumplido');
            $table->boolean('criterio2_2_cumplido')->default(false)->after('criterio2_1_cumplido');
            $table->boolean('criterio2_3_cumplido')->default(false)->after('criterio2_2_cumplido');
            $table->boolean('criterio2_4_cumplido')->default(false)->after('criterio2_3_cumplido');

            // Categoría 3 (2 criterios)
            $table->boolean('criterio3_1_cumplido')->default(false)->after('criterio2_4_cumplido');
            $table->boolean('criterio3_2_cumplido')->default(false)->after('criterio3_1_cumplido');

            // 3. Añadir los campos 'observacion' y 'justificacion' (respuesta del docente)
            $table->text('observacion')->nullable()->after('criterio3_2_cumplido');
            $table->text('justificacion')->nullable()->after('observacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            // Eliminar los 10 nuevos campos
            $table->dropColumn([
                'criterio1_1_cumplido',
                'criterio1_2_cumplido',
                'criterio1_3_cumplido',
                'criterio1_4_cumplido',
                'criterio2_1_cumplido',
                'criterio2_2_cumplido',
                'criterio2_3_cumplido',
                'criterio2_4_cumplido',
                'criterio3_1_cumplido',
                'criterio3_2_cumplido',
            ]);

            // Eliminar 'observacion' y 'justificacion'
            $table->dropColumn('observacion');
            $table->dropColumn('justificacion');

            // Re-añadir las columnas antiguas para el rollback (opcional, solo si quieres volver al estado exacto anterior)
            // Si la idea es que estas columnas antiguas ya no existan en el sistema, puedes omitir este bloque.
            $table->unsignedTinyInteger('p_score')->comment('Puntuación para P (0-4)')->after('modulo_id');
            $table->unsignedTinyInteger('o_score')->comment('Puntuación para O (0-4)')->after('p_score');
            $table->unsignedTinyInteger('s1_score')->comment('Puntuación para S1 (0-2)')->after('o_score');
            $table->unsignedTinyInteger('total_score')->comment('Suma de P+O+S1 (0-10)')->after('s1_score');
        });
    }
};
