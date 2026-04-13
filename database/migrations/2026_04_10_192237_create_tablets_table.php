<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tablets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('ciudad_id')->constrained()->onDelete('cascade');
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie')->unique();
            $table->enum('estatus', ['disponible', 'asignado', 'mantenimiento', 'baja'])->default('disponible');
            $table->text('observaciones')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tablets');
    }
};