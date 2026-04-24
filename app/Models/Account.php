<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'username',
        'password',
        'type',
        'colaborador_id',
        'is_ti',
        'observaciones',
    ];
    public function Colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
    public function index()
    {
        $cuentas = \App\Models\Account::with('colaborador')->get();

        return view('cuentas.index', compact('cuentas'));
    }
}
