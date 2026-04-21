<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('equipos_escritorio_perifericos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_escritorio_id')->constrained('equipos_escritorio')->onDelete('cascade');
        $table->foreignId('periferico_id')->constrained('perifericos')->onDelete('restrict');
        $table->integer('cantidad')->default(1);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('equipos_escritorio_perifericos');
}
};
