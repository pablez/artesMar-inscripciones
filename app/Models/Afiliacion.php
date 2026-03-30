<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afiliacion extends Model
{
    protected $table = 'afiliaciones';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'url',
        'imagen',
    ];
}
