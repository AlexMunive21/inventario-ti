<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('asignaciones_celulares', function (Blueprint $table) {
        $table->text('observaciones_devolucion')->nullable()->after('observaciones');
    });
}

public function down(): void
{
    Schema::table('asignaciones_celulares', function (Blueprint $table) {
        $table->dropColumn('observaciones_devolucion');
    });
}
};
