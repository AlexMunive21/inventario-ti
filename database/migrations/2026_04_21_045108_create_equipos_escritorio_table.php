<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('equipos_escritorio', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Ej: "Escritorio Recepción"
        $table->foreignId('cpu_id')->constrained('componentes')->onDelete('restrict');
        $table->foreignId('area_id')->constrained()->onDelete('cascade');
        $table->foreignId('ciudad_id')->constrained()->onDelete('cascade');
        $table->enum('estatus', ['disponible', 'asignado', 'mantenimiento', 'baja'])->default('disponible');
        $table->text('observaciones')->nullable();
        $table->softDeletes();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('equipos_escritorio');
}
};
