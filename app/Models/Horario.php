<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'titulo',
        'dias',
        'disciplina_id',
        'categoria',
        'hora',
        'sucursal',
        'descripcion',
    ];

    public function disciplina()
    {
        return $this->belongsTo(\App\Models\Disciplina::class, 'disciplina_id');
    }
}
