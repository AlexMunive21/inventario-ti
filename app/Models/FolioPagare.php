<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolioPagare extends Model
{
    protected $table = 'folios_pagare';

    protected $fillable = [
        'asignacion_id',
        'tipo',
        'folio',
    ];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }
}