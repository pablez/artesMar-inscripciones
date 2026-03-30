<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Horario;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'user_id','nombre','telefono','email',
        'disciplina_id','categoria','dias','hora','sucursal',
        'mensaje','comprobante','estado_pago','fecha_pago','notas_admin',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    /**
     * Obtener el horario actual asociado a esta inscripción
     * Busca por disciplina_id, dias, hora (campos que coinciden)
     */
    public function horario_actual()
    {
        return Horario::where([
            ['disciplina_id', '=', $this->disciplina_id],
            ['dias', '=', $this->dias],
            ['hora', '=', $this->hora]
        ])->first();
    }

    /**
     * Obtener sucursal sincronizada del horario actual
     */
    public function getSucursalSincronizada()
    {
        $horario = $this->horario_actual();
        return $horario ? $horario->sucursal : $this->sucursal;
    }

    /**
     * Obtener categoría sincronizada del horario actual
     */
    public function getCategoriaSincronizada()
    {
        $horario = $this->horario_actual();
        return $horario ? $horario->categoria : $this->categoria;
    }

    /**
     * Obtener descripción del horario actual
     */
    public function getDescripcionHorario()
    {
        $horario = $this->horario_actual();
        return $horario ? $horario->descripcion : null;
    }
}

