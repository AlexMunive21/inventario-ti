<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactivacionTablet extends Model
{
    protected $table = 'reactivaciones_tablets';

    protected $fillable = [
        'tablet_id',
        'user_id',
        'tipo_reparacion',
        'fecha_reactivacion',
        'autorizo',
    ];

    public function tablet()
    {
        return $this->belongsTo(Tablet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}