@extends('layouts.app')
@section('title','Inscripciones — JUGA Martial Arts')

@section('content')
@php
  $horarios = [
    ['disciplina'=>'Jiu Jitsu Kids','categoria'=>'4 a 9 años','dias'=>'Lun / Mié / Vie','hora'=>'16:00–17:00','sucursal'=>'Hupermall'],
    ['disciplina'=>'Jiu Jitsu Kids','categoria'=>'9 a 14 años','dias'=>'Lun / Mié / Vie','hora'=>'17:30–18:30','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Box','categoria'=>'14+','dias'=>'Mar / Jue','hora'=>'18:30–19:30','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Jiu Jitsu Adultos','categoria'=>'14+','dias'=>'Lun a Vie','hora'=>'06:00–07:00','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Jiu Jitsu Adultos','categoria'=>'14+','dias'=>'Lun a Vie','hora'=>'18:30–19:30','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Jiu Jitsu Adultos','categoria'=>'14+','dias'=>'Lun a Vie','hora'=>'19:30–20:30','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'MMA','categoria'=>'14+','dias'=>'Lun / Mié / Vie','hora'=>'08:30–09:30','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Funcional y acondicionamiento','categoria'=>'14+','dias'=>'Lun a Vie','hora'=>'10:00–12:00','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Karate y Kick Boxing','categoria'=>'14+','dias'=>'Lun / Mié','hora'=>'06:00–07:00','sucursal'=>'Segunda circunvalación'],
    ['disciplina'=>'Karate y Kick Boxing','categoria'=>'14+','dias'=>'Lun / Mié','hora'=>'18:30–19:30','sucursal'=>'Segunda circunvalación'],
  ];
@endphp

<section class="section">
  <div class="container" style="max-width:720px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

  <h2 class="h2">Inscripciones</h2>
  <p class="mt-2 muted">Deja tus datos y elige tu horario.</p>

  {{-- Información de precios --}}
  <div style="background:#e8f5e8;border:1px solid #28a745;border-radius:10px;padding:20px;margin:20px 0;text-align:center;">
    <h3 style="color:#155724;margin:0 0 15px;font-size:18px;font-weight:600;">Precios de mensualidades</h3>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
      <div style="background:white;border-radius:8px;padding:10px;min-width:100px;text-align:center;">Plan Completo<br><strong>400 Bs</strong></div>
      <div style="background:white;border-radius:8px;padding:10px;min-width:100px;text-align:center;">5dias<br><strong>370 Bs</strong></div>
      <div style="background:white;border-radius:8px;padding:10px;min-width:100px;text-align:center;">Jiu jitsu kids<br><strong>300 Bs</strong></div>
      <div style="background:white;border-radius:8px;padding:10px;min-width:100px;text-align:center;">Clases privadas hasta 2 personas<br><strong>1200 Bs</strong></div>
    </div>
    <p style="margin:15px 0 0;color:#155724;font-size:14px;font-style:italic;">Elige la frecuencia que mejor se adapte a tu rutina</p>
  </div>

    {{-- Mensaje especial para el admin --}}
    @auth
        @if(auth()->user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com'))
            <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:16px;margin:20px 0;">
                <h4 style="color:#856404;margin:0 0 8px;font-size:16px;">Modo administrador</h4>
                <p style="color:#856404;margin:0;font-size:14px;">
                    Estás viendo como administrador. 
                    <a href="{{ route('admin.inscripciones') }}" style="color:#856404;text-decoration:underline;">
                        Ver panel de administración
                    </a> para gestionar todas las inscripciones.
                </p>
            </div>
        @endif
    @endauth

    <form action="{{ route('inscripciones.store') }}" method="post" class="mt-8 card">
      @csrf

      @if (session('status'))
         <div class="mt-2" style="color:#0a7d1c">{{ session('status') }}</div>
      @endif

      <div class="grid grid-2">
        <div>
          <label class="label">Nombre Completo</label>
          <input name="nombre" 
                 type="text" 
                 class="mt-2 input" 
                 value="{{ old('nombre') }}" 
                 placeholder="Ej: Juan Carlos Pérez López"
                 oninput="capitalizarNombre(this)"
                 required>
          @error('nombre')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-1" style="color:#666;font-size:12px;">Ingresa tu nombre y apellido completos (solo letras)</div>
        </div>

        <div>
          <label class="label">Teléfono</label>
          <input name="telefono" 
                 type="tel" 
                 class="mt-2 input" 
                 value="{{ old('telefono') }}"
                 placeholder="Ej: 71234567"
                 maxlength="8"
                 oninput="soloNumeros(this)"
                 required>
          @error('telefono')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-1" style="color:#666;font-size:12px;">Exactamente 8 dígitos</div>
        </div>

        <div style="grid-column:1/-1">
          <label class="label">Correo Electrónico</label>
          <input name="email" 
                 type="email" 
                 class="mt-2 input" 
                 value="{{ old('email') }}" 
                 placeholder="Ej: juan.perez@email.com"
                 required>
          @error('email')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-1" style="color:#666;font-size:12px;">Ingresa un email válido para recibir confirmaciones</div>
        </div>

        <div style="grid-column:1/-1">
          <label class="label">Disciplina</label>
          <select name="disciplina_id" class="mt-2 select" required>
            <option value="" disabled {{ old('disciplina_id') ? '' : 'selected' }}>Selecciona una disciplina…</option>
            @foreach ($disciplinas as $disciplina)
              <option value="{{ $disciplina->id }}" {{ old('disciplina_id') == $disciplina->id ? 'selected' : '' }}>
                {{ $disciplina->nombre }}
              </option>
            @endforeach
          </select>
          @error('disciplina_id')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-1" style="color:#666;font-size:12px;">Elige la disciplina</div>
        </div>

        <div style="grid-column:1/-1">
          <label class="label">Horario Preferido</label>
          <select name="horario" class="mt-2 select" required>
            <option value="" disabled {{ old('horario') ? '' : 'selected' }}>Selecciona un horario…</option>
            @foreach (collect($horarios)->groupBy('disciplina') as $disciplina => $items)
              <optgroup label="{{ $disciplina }}">
                @foreach ($items as $h)
                  @php
                    $value = implode('|', [$h['disciplina'],$h['categoria'],$h['dias'],$h['hora'],$h['sucursal']]);
                    $label = "{$h['categoria']} — {$h['dias']} — {$h['hora']} — {$h['sucursal']}";
                  @endphp
                  <option value="{{ $value }}" {{ old('horario') == $value ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
          @error('horario')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-2" style="color:#666;font-size:12px;">Ejemplo: "9 a 14 años — Lun/Mié/Vie — 17:30–18:30 — Segunda Circunvalación"</div>
        </div>

        <div style="grid-column:1/-1">
          <label class="label">Mensaje (Opcional)</label>
          <textarea name="mensaje" rows="4" class="mt-2 textarea" placeholder="Cuéntanos tu objetivo, experiencia previa o alguna pregunta...">{{ old('mensaje') }}</textarea>
          @error('mensaje')
            <div class="mt-1" style="color:#b00020">{{ $message }}</div>
          @enderror
          <div class="mt-1" style="color:#666;font-size:12px;">Comparte información adicional si lo deseas</div>
        </div>
      </div>

      <div class="mt-6">
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </form>
  </div>
</section>

@push('scripts')
<script>
// Función para capitalizar nombres automáticamente
function capitalizarNombre(input) {
    let valor = input.value;
    
    // Eliminar caracteres no permitidos (números y caracteres especiales excepto espacios)
    valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
    
    // Capitalizar cada palabra
    valor = valor.toLowerCase().replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });
    
    input.value = valor;
    
    // Validación visual
    const palabras = valor.trim().split(/\s+/);
    if (palabras.length >= 2 && valor.trim().length >= 3) {
        input.style.borderColor = '#28a745';
        input.style.backgroundColor = '#f8fff9';
    } else {
        input.style.borderColor = '#dc3545';
        input.style.backgroundColor = '#fff8f8';
    }
}

// Función para permitir solo números en teléfono
function soloNumeros(input) {
    let valor = input.value;
    
    // Eliminar todo lo que no sean dígitos
    valor = valor.replace(/[^0-9]/g, '');
    
    // Limitar a 8 dígitos
    if (valor.length > 8) {
        valor = valor.substring(0, 8);
    }
    
    input.value = valor;
    
    // Validación visual
    if (valor.length === 8) {
        input.style.borderColor = '#28a745';
        input.style.backgroundColor = '#f8fff9';
    } else {
        input.style.borderColor = '#dc3545';
        input.style.backgroundColor = '#fff8f8';
    }
}

// Validar formulario antes del envío
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nombreInput = document.querySelector('input[name="nombre"]');
    const telefonoInput = document.querySelector('input[name="telefono"]');
    
    form.addEventListener('submit', function(e) {
        let esValido = true;
        
    // Validar nombre
    const nombre = nombreInput.value.trim();
    const palabras = nombre.split(/\s+/);
    if (palabras.length < 2 || nombre.length < 3) {
      alert('Por favor ingresa tu nombre y apellido completos (mínimo 2 palabras)');
      nombreInput.focus();
      esValido = false;
      e.preventDefault();
      return;
    }
        
    // Validar teléfono
    if (telefonoInput.value.length !== 8) {
      alert('El teléfono debe tener exactamente 8 dígitos');
      telefonoInput.focus();
      esValido = false;
      e.preventDefault();
      return;
    }
        
    if (esValido) {
      // Mostrar mensaje de procesamiento
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.textContent = 'Procesando...';
        submitBtn.disabled = true;
      }
    }
    });
});
</script>
@endpush
@endsection
