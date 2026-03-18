<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asignaciones_celulares', function (Blueprint $table) {

            // eliminar foreign key primero
            $table->dropForeign(['user_id']);

            // eliminar columna
            $table->dropColumn('user_id');

            // agregar colaborador_id
            $table->foreignId('colaborador_id')
                ->after('celular_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones_celulares', function (Blueprint $table) {

            $table->dropForeign(['colaborador_id']);
            $table->dropColumn('colaborador_id');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }
};
