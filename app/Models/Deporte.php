<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deporte extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
