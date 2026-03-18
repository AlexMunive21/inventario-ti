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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ciudad_id')->constrained()->cascadeOnDelete();

            // Información del equipo
            $table->string('tipo_equipo'); // Laptop, PC, Servidor
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie')->unique();

            $table->enum('estatus', ['Activo', 'Baja', 'Reparacion'])->default('Activo');

            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
