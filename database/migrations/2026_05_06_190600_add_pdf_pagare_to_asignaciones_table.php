<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('asignaciones', function (Blueprint $table) {
        $table->string('pdf_pagare')->nullable()->after('pdf_firmado');
    });
}
public function down(): void
{
    Schema::table('asignaciones', function (Blueprint $table) {
        $table->dropColumn('pdf_pagare');
    });
}
};
