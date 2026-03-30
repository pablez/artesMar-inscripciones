@extends('layouts.app')
@section('title','Panel Admin — Inscripciones')

@section('content')
<section class="section">
  <div class="container" style="max-width:1200px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <h2 class="h2">Panel Administrativo - Inscripciones</h2>
    
    {{-- Navegación del admin --}}
    @include('admin.partials.admin-nav')

    @if (session('status'))
       <div class="mt-4" style="color:#0a7d1c; padding:12px; background:#e8f5e8; border-radius:4px;">
         {{ session('status') }}
       </div>
    @endif

    @if (session('error'))
       <div class="mt-4" style="color:#b00020; padding:12px; background:#ffebee; border-radius:4px;">
         {{ session('error') }}
       </div>
    @endif

    @if($inscripciones->count() > 0)
      <div class="card mt-6">
        <h3 style="margin-bottom:16px;">Inscripciones Registradas ({{ $inscripciones->total() }})</h3>
        
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5; border-bottom:2px solid #ddd;">
                <th style="padding:12px; text-align:left;">Fecha</th>
                <th style="padding:12px; text-align:left;">Nombre</th>
                <th style="padding:12px; text-align:left;">Email</th>
                <th style="padding:12px; text-align:left;">Teléfono</th>
                <th style="padding:12px; text-align:left;">Disciplina</th>
                <th style="padding:12px; text-align:left;">Horario</th>
                <th style="padding:12px; text-align:left;">Comprobante</th>
                <th style="padding:12px; text-align:left;">Estado</th>
                <th style="padding:12px; text-align:left;">Fecha Pago</th>
                <th style="padding:12px; text-align:left;">Usuario</th>
                <th style="padding:12px; text-align:left;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($inscripciones as $inscripcion)
                <tr style="border-bottom:1px solid #eee;">
                  <td style="padding:12px;">{{ $inscripcion->created_at->format('d/m/Y H:i') }}</td>
                  <td style="padding:12px;"><strong>{{ $inscripcion->nombre }}</strong></td>
                  <td style="padding:12px;">{{ $inscripcion->email }}</td>
                  <td style="padding:12px;">{{ $inscripcion->telefono ?: 'N/A' }}</td>
                  <td style="padding:12px;">
                    <span style="background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:4px; font-size:12px;">
                      {{ $inscripcion->disciplina->nombre ?? 'N/A' }}
                    </span>
                  </td>
                  <td style="padding:12px; font-size:12px;">
                    @php
                      $horario_actual = $inscripcion->horario_actual();
                      $categoria = $horario_actual ? $horario_actual->categoria : $inscripcion->categoria;
                      $dias = $horario_actual ? $horario_actual->dias : $inscripcion->dias;
                      $hora = $horario_actual ? $horario_actual->hora : $inscripcion->hora;
                      $sucursal = $horario_actual ? $horario_actual->sucursal : $inscripcion->sucursal;
                    @endphp
                    {{ $categoria ?? '-' }} - {{ $dias }} - {{ $hora }}
                    @if($sucursal)
                      <br><small style="color:#666;">📍 {{ $sucursal }}</small>
                    @endif
                    @if($horario_actual && $horario_actual->descripcion)
                      <br><small style="color:#999; font-style:italic;">{{ $horario_actual->descripcion }}</small>
                    @endif
                  </td>
                  <td style="padding:12px;">
                    @if($inscripcion->comprobante)
                      <a href="{{ url('/uploads/comprobantes/'.$inscripcion->comprobante) }}" target="_blank" style="text-decoration:underline; color:#1976d2;">Ver comprobante</a>
                      <br><small style="color:#666;">{{ $inscripcion->comprobante }}</small>
                    @else
                      <span style="color:#999;">Sin comprobante</span>
                    @endif
                  </td>
                  <td style="padding:12px;">
                    @php
                      $badgeColor = '#ffc107';
                      if($inscripcion->estado_pago === 'pagado') $badgeColor = '#28a745';
                      if($inscripcion->estado_pago === 'rechazado') $badgeColor = '#f44336';
                    @endphp
                    <span style="background:{{ $badgeColor }}; color:white; padding:6px 10px; border-radius:4px; font-size:12px;">
                      {{ ucfirst(str_replace('_',' ',$inscripcion->estado_pago ?? 'pendiente')) }}
                    </span>
                  </td>
                  <td style="padding:12px;">
                    @if($inscripcion->fecha_pago)
                      {{ \Carbon\Carbon::parse($inscripcion->fecha_pago)->format('d/m/Y H:i') }}
                    @else
                      -
                    @endif
                  </td>
                  <td style="padding:12px; font-size:12px;">
                    @if($inscripcion->user)
                      {{ $inscripcion->user->name }}
                      <br><small style="color:#666;">{{ $inscripcion->user->email }}</small>
                    @else
                      <span style="color:#666;">Sin cuenta</span>
                    @endif
                  </td>
                  <td style="padding:12px;">
                    <div style="display:flex; gap:8px; align-items:center;">
                      <form action="{{ route('admin.inscripciones.estado', $inscripcion->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="notas_admin" value="">
                        <input type="hidden" name="estado_pago" value="pagado">
                        <button type="submit" style="background:#28a745; color:white; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; font-size:12px;">Marcar pagado</button>
                      </form>

                      <form action="{{ route('admin.inscripciones.estado', $inscripcion->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="notas_admin" value="Pago rechazado">
                        <input type="hidden" name="estado_pago" value="rechazado">
                        <button type="submit" style="background:#f44336; color:white; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; font-size:12px;">Marcar rechazado</button>
                      </form>

                      <form action="{{ route('admin.inscripciones.destroy', $inscripcion->id) }}" method="POST" 
                          onsubmit="return confirm('¿Estás seguro de eliminar esta inscripción?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#9e9e9e; color:white; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; font-size:12px;">Eliminar</button>
                      </form>
                    </div>
                  </td>
                </tr>
                @if($inscripcion->mensaje)
                  <tr style="background:#f9f9f9;">
                    <td colspan="8" style="padding:8px 12px; font-style:italic; color:#666; font-size:14px;">
                      <strong>Mensaje:</strong> {{ $inscripcion->mensaje }}
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          {{ $inscripciones->links() }}
        </div>
      </div>
    @else
      <div class="card mt-6" style="text-align:center; padding:40px;">
        <h3 style="color:#666;">No hay inscripciones registradas</h3>
        <p style="color:#999; margin-top:8px;">Las nuevas inscripciones aparecerán aquí.</p>
      </div>
    @endif

  </div>
</section>
@endsection