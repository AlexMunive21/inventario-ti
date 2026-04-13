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
    Schema::create('reactivaciones_equipos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('tipo_reparacion', ['reemplazo_pieza', 'mantenimiento', 'limpieza', 'otro']);
        $table->date('fecha_reactivacion');
        $table->string('autorizo');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('reactivaciones_equipos');
}
};
