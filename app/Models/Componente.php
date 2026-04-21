<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Componente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tipo',
        'marca',
        'modelo',
        'numero_serie',
        'area_id',
        'ciudad_id',
        'estatus',
        'observaciones',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function equipoEscritorioComoCpu()
    {
        return $this->hasOne(EquipoEscritorio::class, 'cpu_id');
    }

    public function equiposEscritorioComoMonitor()
    {
        return $this->belongsToMany(
            EquipoEscritorio::class,
            'equipos_escritorio_monitores',
            'monitor_id',
            'equipo_escritorio_id'
        );
    }
}