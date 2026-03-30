@extends('layouts.app')
@section('title','Panel Admin — Horarios')

@section('content')
<section class="section">
  <div class="container" style="max-width:1200px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <h2 class="h2">Panel Administrativo - Horarios</h2>
    
    {{-- Navegación del admin --}}
    @include('admin.partials.admin-nav')

    @if (session('status'))
       <div class="mt-4" style="color:#0a7d1c; padding:12px; background:#e8f5e8; border-radius:4px;">
         {{ session('status') }}
       </div>
    @endif

    {{-- Formulario para nuevo horario --}}
    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">➕ Nuevo Horario</h3>
      <form action="{{ route('admin.horarios.store') }}" method="post">
        @csrf
          <div class="grid grid-3">
          <div>
            <label class="label">Título</label>
            <input name="titulo" type="text" class="mt-2 input" value="{{ old('titulo') }}" placeholder="Ej: Jiu Jitsu Adultos" required>
            @error('titulo')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Disciplina</label>
            <select name="disciplina_id" class="mt-2 select">
              <option value="">-- Selecciona una disciplina (opcional) --</option>
              @foreach($disciplinas as $d)
                <option value="{{ $d->id }}" {{ old('disciplina_id') == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>
              @endforeach
            </select>
            @error('disciplina_id')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Días</label>
            <select name="dias" class="mt-2 select" required>
              <option value="" disabled {{ old('dias') ? '' : 'selected' }}>Selecciona días...</option>
              <option value="Lunes a Viernes" {{ old('dias') == 'Lunes a Viernes' ? 'selected' : '' }}>Lunes a Viernes</option>
              <option value="Lun / Mié / Vie" {{ old('dias') == 'Lun / Mié / Vie' ? 'selected' : '' }}>Lun / Mié / Vie</option>
              <option value="Mar / Jue" {{ old('dias') == 'Mar / Jue' ? 'selected' : '' }}>Mar / Jue</option>
              <option value="Sábados" {{ old('dias') == 'Sábados' ? 'selected' : '' }}>Sábados</option>
              <option value="Lun / Mié" {{ old('dias') == 'Lun / Mié' ? 'selected' : '' }}>Lun / Mié</option>
            </select>
            @error('dias')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Horario</label>
            <input name="hora" type="text" class="mt-2 input" value="{{ old('hora') }}" placeholder="Ej: 18:30–19:30" required>
            @error('hora')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Categoría (ej. 9 a 14 años)</label>
            <input name="categoria" type="text" class="mt-2 input" value="{{ old('categoria') }}" placeholder="Ej: 9 a 14 años">
            @error('categoria')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Sucursal</label>
            <input name="sucursal" type="text" class="mt-2 input" value="{{ old('sucursal') }}" placeholder="Ej: Segunda circunvalación">
            @error('sucursal')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div style="grid-column:1/-1">
            <label class="label">Descripción (opcional)</label>
            <textarea name="descripcion" rows="2" class="mt-2 textarea" placeholder="Información adicional sobre el horario...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Crear Horario</button>
        </div>
      </form>
    </div>

    {{-- Lista de horarios --}}
    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">📅 Horarios Registrados ({{ $horarios->count() }})</h3>
      
      @if($horarios->count() > 0)
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f5f5f5; border-bottom:2px solid #ddd;">
                <th style="padding:12px; text-align:left;">ID</th>
                <th style="padding:12px; text-align:left;">Título</th>
                <th style="padding:12px; text-align:left;">Disciplina</th>
                <th style="padding:12px; text-align:left;">Días</th>
                <th style="padding:12px; text-align:left;">Hora</th>
                <th style="padding:12px; text-align:left;">Categoría</th>
                <th style="padding:12px; text-align:left;">Sucursal</th>
                <th style="padding:12px; text-align:left;">Descripción</th>
                <th style="padding:12px; text-align:left;">Fecha</th>
                <th style="padding:12px; text-align:center;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($horarios as $horario)
                <tr style="border-bottom:1px solid #eee;">
                  <td style="padding:12px;"><strong>{{ $horario->id }}</strong></td>
                  <td style="padding:12px;">
                    <strong>{{ $horario->titulo }}</strong>
                  </td>
                  <td style="padding:12px;">
                    @if($horario->disciplina)
                      <span style="background:#f0f0f0; padding:4px 8px; border-radius:4px; font-size:12px;">{{ $horario->disciplina->nombre }}</span>
                    @else
                      <span style="color:#999; font-size:12px;">—</span>
                    @endif
                  </td>
                  <td style="padding:12px;">
                    <span style="background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:4px; font-size:12px;">{{ $horario->dias }}</span>
                  </td>
                  <td style="padding:12px;">
                    <span style="background:#fff3cd; color:#856404; padding:4px 8px; border-radius:4px; font-size:12px;">
                      {{ $horario->hora }}
                    </span>
                  </td>
                  <td style="padding:12px;">{{ $horario->categoria ?: '—' }}</td>
                  <td style="padding:12px; max-width:200px;">{{ $horario->sucursal ?: '—' }}</td>
                  <td style="padding:12px; max-width:200px; font-size:12px;">{{ Str::limit($horario->descripcion ?? '', 50) ?: '—' }}</td>
                  <td style="padding:12px; font-size:12px; color:#666;">
                    {{ $horario->created_at->format('d/m/Y') }}
                  </td>
                  <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center;">
                      <button onclick="editarHorario({{ $horario->id }}, '{{ addslashes($horario->titulo) }}', '{{ $horario->dias }}', '{{ addslashes($horario->hora) }}', '{{ addslashes($horario->descripcion ?? '') }}', '{{ $horario->disciplina_id }}', '{{ addslashes($horario->categoria ?? '') }}', '{{ addslashes($horario->sucursal ?? '') }}')"
                              style="padding:4px 8px; background:#ffc107; color:white; border:none; border-radius:4px; font-size:12px; cursor:pointer;">
                        ✏️ Editar
                      </button>
                      <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este horario?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding:4px 8px; background:#dc3545; color:white; border:none; border-radius:4px; font-size:12px; cursor:pointer;">
                          🗑️ Eliminar
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div style="text-align:center; padding:40px;">
          <h4 style="color:#666;">No hay horarios registrados</h4>
          <p style="color:#999; margin-top:8px;">Crea el primer horario usando el formulario de arriba.</p>
        </div>
      @endif
    </div>

    {{-- Modal para editar --}}
    <div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:24px; border-radius:8px; width:90%; max-width:600px;">
        <h3 style="margin-bottom:16px;">✏️ Editar Horario</h3>
        <form id="editForm" method="post">
          @csrf
          @method('PUT')
          <div class="grid grid-3">
            <div>
              <label class="label">Título/Disciplina</label>
              <input id="editTitulo" name="titulo" type="text" class="mt-2 input" required>
            </div>
            <div>
              <label class="label">Disciplina</label>
              <select id="editDisciplina" name="disciplina_id" class="mt-2 select">
                <option value="">-- Seleccionar --</option>
                @foreach($disciplinas as $d)
                  <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="label">Días</label>
              <select id="editDias" name="dias" class="mt-2 select" required>
                <option value="Lunes a Viernes">Lunes a Viernes</option>
                <option value="Lun / Mié / Vie">Lun / Mié / Vie</option>
                <option value="Mar / Jue">Mar / Jue</option>
                <option value="Sábados">Sábados</option>
                <option value="Lun / Mié">Lun / Mié</option>
              </select>
            </div>
            <div>
              <label class="label">Horario</label>
              <input id="editHora" name="hora" type="text" class="mt-2 input" required>
            </div>
            <div>
              <label class="label">Categoría</label>
              <input id="editCategoria" name="categoria" type="text" class="mt-2 input">
            </div>
            <div>
              <label class="label">Sucursal</label>
              <input id="editSucursal" name="sucursal" type="text" class="mt-2 input">
            </div>
            <div style="grid-column:1/-1">
              <label class="label">Descripción (opcional)</label>
              <textarea id="editDescripcion" name="descripcion" rows="2" class="mt-2 textarea"></textarea>
            </div>
          </div>
          <div class="mt-4" style="display:flex; gap:12px;">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" onclick="cerrarModal()" class="btn" style="background:#6c757d; color:white;">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

@push('scripts')
<script>
function editarHorario(id, titulo, dias, hora, descripcion, disciplina_id, categoria, sucursal) {
  document.getElementById('editModal').style.display = 'block';
  document.getElementById('editForm').action = '{{ route("admin.horarios.update", ":id") }}'.replace(':id', id);
  document.getElementById('editTitulo').value = titulo;
  document.getElementById('editDias').value = dias;
  document.getElementById('editHora').value = hora;
  document.getElementById('editDescripcion').value = descripcion || '';
  document.getElementById('editDisciplina').value = disciplina_id || '';
  document.getElementById('editCategoria').value = categoria || '';
  document.getElementById('editSucursal').value = sucursal || '';
}

function cerrarModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Cerrar modal al hacer click fuera
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>
@endpush
@endsection