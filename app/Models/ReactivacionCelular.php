<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactivacionCelular extends Model
{
    protected $table = 'reactivaciones_celulares';

    protected $fillable = [
        'celular_id',
        'user_id',
        'tipo_reparacion',
        'fecha_reactivacion',
        'autorizo',
    ];

    public function celular()
    {
        return $this->belongsTo(Celular::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
