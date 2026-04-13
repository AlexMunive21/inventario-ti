<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactivacionEquipo extends Model
{
    protected $table = 'reactivaciones_equipos';

    protected $fillable = [
        'equipo_id',
        'user_id',
        'tipo_reparacion',
        'fecha_reactivacion',
        'autorizo',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
