<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('componentes', function (Blueprint $table) {
        $table->id();
        $table->enum('tipo', ['cpu', 'monitor']);
        $table->string('marca');
        $table->string('modelo');
        $table->string('numero_serie')->unique();
        $table->foreignId('area_id')->constrained()->onDelete('cascade');
        $table->foreignId('ciudad_id')->constrained()->onDelete('cascade');
        $table->enum('estatus', ['disponible', 'en_uso', 'mantenimiento', 'baja'])->default('disponible');
        $table->text('observaciones')->nullable();
        $table->softDeletes();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('componentes');
}
};
