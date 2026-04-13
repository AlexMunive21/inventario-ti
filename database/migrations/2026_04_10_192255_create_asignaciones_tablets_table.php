<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_tablets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tablet_id')->constrained()->onDelete('cascade');
            $table->foreignId('colaborador_id')->constrained()->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_tablets');
    }
};