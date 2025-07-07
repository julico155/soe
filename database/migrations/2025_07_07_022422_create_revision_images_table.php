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
        Schema::create('revision_images', function (Blueprint $table) {
            $table->id();
            // Clave foránea a la tabla 'revisiones'
            $table->foreignId('revision_id')->constrained('revisiones')->onDelete('cascade');
            // Columna para almacenar la imagen codificada en Base64
            // Usamos LONGTEXT para permitir imágenes de mayor tamaño
            $table->longText('image_base64');
            // Opcional: para guardar el nombre original del archivo
            $table->string('filename')->nullable();
            // Opcional: para guardar el tipo MIME del archivo
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_images');
    }
};
