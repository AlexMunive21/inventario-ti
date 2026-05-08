<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoEscritorio extends Model
{
    use SoftDeletes;

    protected $table = 'equipos_escritorio';

    protected $fillable = [
        'nombre',
        'cpu_id',
        'area_id',
        'ciudad_id',
        'estatus',
        'observaciones',
    ];

    public function cpu()
    {
        return $this->belongsTo(Componente::class, 'cpu_id');
    }

    public function monitores()
    {
        return $this->belongsToMany(
            Componente::class,
            'equipos_escritorio_monitores',
            'equipo_escritorio_id',
            'monitor_id'
        );
    }

    public function perifericos()
    {
        return $this->belongsToMany(
            Periferico::class,
            'equipos_escritorio_perifericos',
            'equipo_escritorio_id',
            'periferico_id'
        )->withPivot('cantidad');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionEscritorio::class);
    }
}