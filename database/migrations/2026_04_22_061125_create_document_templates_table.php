<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('document_templates', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->enum('tipo', [
            'responsiva_equipo',
            'responsiva_celular',
            'responsiva_tablet',
            'pagare_laptop',
            'pagare_tablet',
            'ficha_tecnica',
        ]);
        $table->string('archivo');
        $table->foreignId('user_id')->constrained();
        $table->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('document_templates'); }
};
