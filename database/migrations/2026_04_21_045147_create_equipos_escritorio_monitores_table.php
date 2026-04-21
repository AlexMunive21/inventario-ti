<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('equipos_escritorio_monitores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_escritorio_id')->constrained('equipos_escritorio')->onDelete('cascade');
        $table->foreignId('monitor_id')->constrained('componentes')->onDelete('restrict');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('equipos_escritorio_monitores');
}
};
