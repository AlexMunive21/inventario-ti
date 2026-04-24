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
        Schema::table('asignaciones_tablets', function (Blueprint $table) {
            $table->string('pdf_firmado')->nullable()->after('observaciones_devolucion');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignaciones_tablets', function (Blueprint $table) {
            $table->dropColumn('pdf_firmado');
        });
    }
};
