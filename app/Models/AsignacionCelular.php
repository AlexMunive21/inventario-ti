<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionCelular extends Model
{
    protected $table = 'asignaciones_celulares';

    protected $fillable = [
        'celular_id',
        'colaborador_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'observaciones',
        'observaciones_devolucion',
        'activa'
    ];

    // Relación con celular
    public function celular()
    {
        return $this->belongsTo(Celular::class);
    }

    // Relación con colaborador
    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}