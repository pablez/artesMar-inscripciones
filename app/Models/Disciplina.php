<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','descripcion','activo'];

    // Relaciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}