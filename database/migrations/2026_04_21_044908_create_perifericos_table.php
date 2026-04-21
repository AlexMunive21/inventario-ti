<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('perifericos', function (Blueprint $table) {
        $table->id();
        $table->enum('tipo', ['teclado', 'mouse']);
        $table->string('marca');
        $table->string('modelo');
        $table->foreignId('area_id')->constrained()->onDelete('cascade');
        $table->integer('cantidad_total')->default(1);
        $table->integer('cantidad_disponible')->default(1);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('perifericos');
}
};
