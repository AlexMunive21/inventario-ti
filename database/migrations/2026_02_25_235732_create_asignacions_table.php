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
    Schema::create('asignaciones', function (Blueprint $table) {
        $table->id();

        $table->foreignId('equipo_id')->constrained()->onDelete('cascade');
        $table->foreignId('colaborador_id')->constrained()->onDelete('cascade');

        $table->date('fecha_asignacion');
        $table->date('fecha_devolucion')->nullable();

        $table->text('observaciones')->nullable();

        $table->boolean('activa')->default(1);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
