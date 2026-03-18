<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE equipos 
            MODIFY estatus 
            ENUM('disponible','mantenimiento','asignado','baja') 
            NOT NULL DEFAULT 'disponible'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE equipos 
            MODIFY estatus 
            ENUM('Activo','Reparacion','Baja') 
            NOT NULL
        ");
    }
};
