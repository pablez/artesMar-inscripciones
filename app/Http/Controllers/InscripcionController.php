<?php
namespace App\Http\Controllers;

use App\Http\Requests\InscripcionRequest;  
use App\Models\Inscripcion;
use App\Models\Disciplina;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function create(): View
    {
        $disciplinas = Disciplina::where('activo', true)->get();
        return view('inscripciones', compact('disciplinas'));
    }

    public function store(InscripcionRequest $request)
    {
        
        [$disciplina,$categoria,$dias,$hora,$sucursal] =
            array_pad(explode('|', $request->horario), 5, null);

        $inscripcion = Inscripcion::create([
            'nombre'        => $request->nombre,
            'telefono'      => $request->telefono,
            'email'         => $request->email,
            'disciplina_id' => $request->disciplina_id,
            'categoria'     => $categoria,
            'dias'          => $dias,
            'hora'          => $hora,
            'sucursal'      => $sucursal,
            'mensaje'       => $request->mensaje,
            'user_id'       => auth()->id() ?? null,
        ]);

        return view('inscripcion-exitosa', compact('inscripcion'))
               ->with('status','¡Inscripción registrada exitosamente!');
    }

    public function success($id)
    {
        $inscripcion = Inscripcion::with('disciplina')->findOrFail($id);
        
        
        if (auth()->id() !== $inscripcion->user_id && 
            auth()->user()->email !== env('ADMIN_EMAIL', 'admin@artesmar.com')) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        return view('inscripcion-exitosa', compact('inscripcion'));
    }

    public function comprobante($id, Request $request)
    {
        $inscripcion = Inscripcion::with('disciplina')->findOrFail($id);
        
        
        if (auth()->id() !== $inscripcion->user_id && 
            auth()->user()->email !== env('ADMIN_EMAIL', 'admin@artesmar.com')) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        
        $request->validate([
            'comprobante' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120' 
        ]);

        // Guardar el archivo
        $fileName = 'comprobante_' . $inscripcion->id . '_' . time() . '.' . $request->file('comprobante')->getClientOriginalExtension();
        $path = $request->file('comprobante')->move(public_path('uploads/comprobantes'), $fileName);

        // Actualizar la inscripción con el comprobante
        $inscripcion->update([
            'comprobante' => $fileName,
            'estado_pago' => 'pendiente_verificacion'
        ]);

        // Retornar sin integración de WhatsApp: el equipo verificará el comprobante
        return redirect()->back()
            ->with('success', '¡Comprobante enviado exitosamente! Nuestro equipo verificará tu pago en las próximas 24 horas.');
    }
}
