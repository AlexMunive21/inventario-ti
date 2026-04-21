<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('asignaciones_escritorio', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_escritorio_id')->constrained('equipos_escritorio')->onDelete('cascade');
        $table->foreignId('colaborador_id')->constrained()->onDelete('cascade');
        $table->date('fecha_asignacion');
        $table->date('fecha_devolucion')->nullable();
        $table->text('observaciones')->nullable();
        $table->text('observaciones_devolucion')->nullable();
        $table->string('pdf_firmado')->nullable();
        $table->boolean('activa')->default(1);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('asignaciones_escritorio');
}
};
