<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('equipos_escritorio', function (Blueprint $table) {
        $table->dropForeign(['area_id']);
        $table->dropForeign(['ciudad_id']);
        $table->dropColumn(['area_id', 'ciudad_id']);
    });
}

public function down(): void
{
    Schema::table('equipos_escritorio', function (Blueprint $table) {
        $table->foreignId('area_id')->nullable()->constrained();
        $table->foreignId('ciudad_id')->nullable()->constrained();
    });
}
};
