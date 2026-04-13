<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Model
{
    use SoftDeletes;

    protected $table = 'colaboradores';

    protected $fillable = [
        'nombre',
        'puesto',
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'correo',
        'telefono',
        'area_id',
        'ciudad_id',
        'activo',
        'fecha_baja'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }
    
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function asignacionesCelulares()
    {
        return $this->hasMany(AsignacionCelular::class, 'colaborador_id');
    }

    public function asignacionesEquipos()
    {   
        return $this->hasMany(Asignacion::class, 'colaborador_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
    public function asignacionesTablets()
    {
        return $this->hasMany(AsignacionTablet::class, 'colaborador_id');
    }

}