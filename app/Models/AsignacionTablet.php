<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionTablet extends Model
{
    protected $table = 'asignaciones_tablets';

    protected $fillable = [
        'tablet_id',
        'colaborador_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'observaciones',
        'observaciones_devolucion',
        'activa'
    ];

    public function tablet()
    {
        return $this->belongsTo(Tablet::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}