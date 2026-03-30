<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Afiliacion;
use App\Models\Horario;
use App\Models\Deporte;
use App\Models\Disciplina;

class AdminController extends Controller
{
    public function inscripciones()
    {
        // Verificar que sea admin
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $inscripciones = Inscripcion::with(['user', 'disciplina'])
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(20);

        return view('admin.inscripciones', compact('inscripciones'));
    }

    public function afiliaciones()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $afiliaciones = Afiliacion::orderBy('created_at', 'desc')->get();
        return view('admin.afiliaciones', compact('afiliaciones'));
    }

    public function horarios()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $horarios = Horario::orderBy('created_at', 'desc')->get();
        $disciplinas = Disciplina::orderBy('nombre')->get();
        return view('admin.horarios', compact('horarios', 'disciplinas'));
    }

    public function deportes()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $deportes = Deporte::orderBy('created_at', 'desc')->get();
        return view('admin.deportes', compact('deportes'));
    }

    public function storeDeporte(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        Deporte::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.deportes')
                        ->with('status', 'Deporte creado correctamente.');
    }

    public function updateDeporte(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $deporte = Deporte::findOrFail($id);
        $deporte->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.deportes')->with('status', 'Deporte actualizado correctamente.');
    }

    public function destroyDeporte($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $deporte = Deporte::findOrFail($id);
        $deporte->delete();

        return redirect()->route('admin.deportes')->with('status', 'Deporte eliminado correctamente.');
    }

    public function disciplinas()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $disciplinas = Disciplina::withCount('inscripciones')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('admin.disciplinas', compact('disciplinas'));
    }

    public function storeDisciplina(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean'
        ]);

        Disciplina::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo') ? (bool)$request->activo : true
        ]);

        return redirect()->route('admin.disciplinas')->with('status', 'Disciplina creada correctamente.');
    }

    public function updateDisciplina(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean'
        ]);

        $disciplina = Disciplina::findOrFail($id);
        $disciplina->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo') ? (bool)$request->activo : $disciplina->activo
        ]);

        return redirect()->route('admin.disciplinas')->with('status', 'Disciplina actualizada correctamente.');
    }

    public function destroyDisciplina($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();

        return redirect()->route('admin.disciplinas')->with('status', 'Disciplina eliminada correctamente.');
    }

    public function destroyInscripcion($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();

        return redirect()->route('admin.inscripciones')
                        ->with('status', 'Inscripción eliminada correctamente.');
    }

    // Actualizar estado de pago (pagado / rechazado / pendiente)
    public function updateEstado(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $request->validate([
            'estado_pago' => 'required|in:pendiente,pagado,rechazado,pendiente_verificacion',
            'notas_admin' => 'nullable|string|max:1000'
        ]);

        $inscripcion = Inscripcion::findOrFail($id);
        $data = [
            'estado_pago' => $request->estado_pago,
            'notas_admin' => $request->notas_admin,
        ];

        if ($request->estado_pago === 'pagado') {
            $data['fecha_pago'] = now();
        } else {
            $data['fecha_pago'] = null;
        }

        $inscripcion->update($data);

        return redirect()->route('admin.inscripciones')->with('status', 'Estado de pago actualizado.');
    }

    // ===== GESTIÓN DE AFILIACIONES =====
    public function storeAfiliacion(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }


        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url' => 'nullable|url',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'url' => $request->url
        ];

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $path = $file->store('afiliaciones', 'public');
            $data['imagen'] = $path;
        }

        Afiliacion::create($data);

        return redirect()->route('admin.afiliaciones')
                        ->with('status', 'Afiliación creada correctamente.');
    }

    public function updateAfiliacion(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }


        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url' => 'nullable|url',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $afiliacion = Afiliacion::findOrFail($id);

        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'url' => $request->url
        ];

        if ($request->hasFile('imagen')) {
            // eliminar imagen anterior si existe
            if ($afiliacion->imagen && \Storage::disk('public')->exists($afiliacion->imagen)) {
                \Storage::disk('public')->delete($afiliacion->imagen);
            }
            $file = $request->file('imagen');
            $path = $file->store('afiliaciones', 'public');
            $data['imagen'] = $path;
        }

        $afiliacion->update($data);

        return redirect()->route('admin.afiliaciones')
                        ->with('status', 'Afiliación actualizada correctamente.');
    }

    public function destroyAfiliacion($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $afiliacion = Afiliacion::findOrFail($id);
        $afiliacion->delete();

        return redirect()->route('admin.afiliaciones')
                        ->with('status', 'Afiliación eliminada correctamente.');
    }

    // ===== GESTIÓN DE HORARIOS =====
    public function storeHorario(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }


        $request->validate([
            'titulo' => 'required|string|max:255',
            'dias' => 'required|string',
            'disciplina_id' => 'nullable|integer|exists:disciplinas,id',
            'categoria' => 'nullable|string|max:255',
            'hora' => 'required|string',
            'sucursal' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        Horario::create([
            'titulo' => $request->titulo,
            'dias' => $request->dias,
            'disciplina_id' => $request->disciplina_id,
            'categoria' => $request->categoria,
            'hora' => $request->hora,
            'sucursal' => $request->sucursal,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.horarios')
                        ->with('status', 'Horario creado correctamente.');
    }

    public function updateHorario(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }


        $request->validate([
            'titulo' => 'required|string|max:255',
            'dias' => 'required|string',
            'disciplina_id' => 'nullable|integer|exists:disciplinas,id',
            'categoria' => 'nullable|string|max:255',
            'hora' => 'required|string',
            'sucursal' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update([
            'titulo' => $request->titulo,
            'dias' => $request->dias,
            'disciplina_id' => $request->disciplina_id,
            'categoria' => $request->categoria,
            'hora' => $request->hora,
            'sucursal' => $request->sucursal,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.horarios')
                        ->with('status', 'Horario actualizado correctamente.');
    }

    public function destroyHorario($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('admin.horarios')
                        ->with('status', 'Horario eliminado correctamente.');
    }

    private function isAdmin()
    {
        return auth()->check() && auth()->user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com');
    }
}
