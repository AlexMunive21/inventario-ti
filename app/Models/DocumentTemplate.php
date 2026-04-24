<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $table = 'document_templates';

    protected $fillable = ['nombre', 'tipo', 'archivo', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function etiquetas(): array
    {
        return [
            'responsiva_equipo'  => 'Responsiva — Equipo de Cómputo / Laptop',
            'responsiva_celular' => 'Responsiva — Celular',
            'responsiva_tablet'  => 'Responsiva — Tablet',
            'pagare_laptop'      => 'Pagaré — Laptop',
            'pagare_tablet'      => 'Pagaré — Tablet',
            'ficha_tecnica'      => 'Ficha Técnica — PC Escritorio',
        ];
    }
}