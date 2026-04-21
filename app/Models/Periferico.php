<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periferico extends Model
{
    protected $table = 'perifericos';

    protected $fillable = [
        'tipo',
        'marca',
        'modelo',
        'area_id',
        'cantidad_total',
        'cantidad_disponible',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function equiposEscritorio()
    {
        return $this->belongsToMany(
            EquipoEscritorio::class,
            'equipos_escritorio_perifericos',
            'periferico_id',
            'equipo_escritorio_id'
        )->withPivot('cantidad');
    }
}