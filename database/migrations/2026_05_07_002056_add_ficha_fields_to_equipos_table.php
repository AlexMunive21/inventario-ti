<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('equipos', function (Blueprint $table) {
        $table->string('nombre_equipo')->nullable()->after('numero_serie'); // nombre en red
        $table->string('correo')->nullable()->after('nombre_equipo');       // correo asignado
        $table->string('estado')->nullable()->after('correo');              // estado (Puebla, CDMX...)
    });
}

public function down(): void
{
    Schema::table('equipos', function (Blueprint $table) {
        $table->dropColumn(['nombre_equipo', 'correo', 'estado']);
    });
}
};
