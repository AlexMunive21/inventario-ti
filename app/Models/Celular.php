<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Celular extends Model
{
    protected $table = 'celulares';

    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'ciudad_id',
        'marca',
        'modelo',
        'imei',
        'numero_telefono',
        'estatus',
        'observaciones'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCelular::class);
    }

    public function asignacionActiva()
    {
        return $this->hasOne(AsignacionCelular::class)
            ->whereNull('fecha_liberacion');
    }
}