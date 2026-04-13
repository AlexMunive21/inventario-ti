<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tablet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'ciudad_id',
        'marca',
        'modelo',
        'numero_serie',
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
        return $this->hasMany(AsignacionTablet::class);
    }
}