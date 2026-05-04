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
    Schema::create('folios_pagare', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asignacion_id')->constrained()->onDelete('cascade');
        $table->string('tipo'); // pagare_laptop, pagare_tablet
        $table->integer('folio')->unique();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios_pagare');
    }
};
