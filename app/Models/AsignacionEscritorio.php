<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionEscritorio extends Model
{
    protected $table = 'asignaciones_escritorio';

    protected $fillable = [
        'equipo_escritorio_id',
        'colaborador_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'observaciones',
        'observaciones_devolucion',
        'pdf_firmado',
        'activa',
    ];

    public function equipoEscritorio()
    {
        return $this->belongsTo(EquipoEscritorio::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}