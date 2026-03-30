<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscripcionRequest extends FormRequest
{
    public function authorize(): bool 
    { 
        return true; 
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required','string','max:120','regex:/^[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ ]+\s+[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/'],

            'telefono'     => ['required','digits:8'],

            'email'        => ['required','email','max:120'],
        
            'disciplina_id' => ['required','exists:disciplinas,id'],
            
            'horario'      => ['required','string'],

            'mensaje'      => ['nullable','string','max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.regex'           => 'Debe ingresar nombre y apellido. Cada uno debe comenzar con mayúscula y solo contener letras.',
            'telefono.required'      => 'El teléfono es obligatorio.',
            'telefono.digits'        => 'El teléfono debe tener exactamente 8 dígitos numéricos.',
            'disciplina_id.required' => 'Debe elegir una disciplina.',
            'disciplina_id.exists'   => 'La disciplina seleccionada no es válida.',
            'horario.required'       => 'Debe elegir un horario.',
        ];
    }
}
