<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'ciudad_id',
        'tipo_equipo',
        'marca',
        'modelo',
        'numero_serie',
        'estatus',
        'observaciones'
    ];

    // Relaciones
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
    public function reactivaciones()
    {
        return $this->hasMany(ReactivacionEquipo::class);
    }
    
}
