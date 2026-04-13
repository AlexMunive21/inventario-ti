<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    protected $fillable = [
        'equipo_id',
        'colaborador_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'observaciones',
        'observaciones_devolucion',
        'activa'
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}
