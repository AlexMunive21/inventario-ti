<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();

            $table->enum('genero', ['Hombre', 'Mujer', 'Otro']);

            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();

            // Relaciones
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ciudad_id')->constrained()->cascadeOnDelete();

            $table->boolean('activo')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradors');
    }
};
