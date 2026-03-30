@extends('layouts.app')
@section('title','Panel Admin — Afiliaciones')

@section('content')
<section class="section">
  <div class="container" style="max-width:1200px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <h2 class="h2">Panel Administrativo - Afiliaciones</h2>
    
    {{-- Navegación del admin --}}
    @include('admin.partials.admin-nav')

    @if (session('status'))
       <div class="mt-4" style="color:#0a7d1c; padding:12px; background:#e8f5e8; border-radius:4px;">
         {{ session('status') }}
       </div>
    @endif

    {{-- Formulario para nueva afiliación --}}
    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">➕ Nueva Afiliación</h3>
      <form action="{{ route('admin.afiliaciones.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
          <div>
            <label class="label">Título</label>
            <input name="titulo" type="text" class="mt-2 input" value="{{ old('titulo') }}" required>
            @error('titulo')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">URL (opcional)</label>
            <input name="url" type="url" class="mt-2 input" value="{{ old('url') }}" placeholder="https://...">
            @error('url')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div style="grid-column:1/-1">
            <label class="label">Descripción</label>
            <textarea name="descripcion" rows="3" class="mt-2 textarea" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Imagen (opcional)</label>
            <input type="file" name="imagen" accept="image/*" class="mt-2 input">
            @error('imagen')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Crear Afiliación</button>
        </div>
      </form>
    </div>

    {{-- Lista de afiliaciones --}}
    <div class="card mt-6">
  <h3 style="margin-bottom:16px;">Afiliaciones Registradas ({{ $afiliaciones->count() }})</h3>
      
      @if($afiliaciones->count() > 0)
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f5f5f5; border-bottom:2px solid #ddd;">
                <th style="padding:12px; text-align:left;">ID</th>
                <th style="padding:12px; text-align:left;">Título</th>
                <th style="padding:12px; text-align:left;">Descripción</th>
                <th style="padding:12px; text-align:left;">URL</th>
                <th style="padding:12px; text-align:left;">Fecha</th>
                <th style="padding:12px; text-align:center;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($afiliaciones as $afiliacion)
                <tr style="border-bottom:1px solid #eee;">
                  <td style="padding:12px;"><strong>{{ $afiliacion->id }}</strong></td>
                  <td style="padding:12px;">
                    <strong>{{ $afiliacion->titulo }}</strong>
                  </td>
                  <td style="padding:12px; max-width:300px;">
                    {{ Str::limit($afiliacion->descripcion, 100) }}
                  </td>
                  <td style="padding:12px;">
                    @if($afiliacion->imagen)
                      <img src="{{ asset('storage/'.$afiliacion->imagen) }}" alt="thumb" style="height:40px; object-fit:cover; border-radius:6px;">
                    @else
                      <span style="color:#999;">Sin imagen</span>
                    @endif
                  </td>
                  <td style="padding:12px;">
                    @if($afiliacion->url)
                      <a href="{{ $afiliacion->url }}" target="_blank" style="color:#007bff; text-decoration:none;">Ver enlace</a>
                    @else
                      <span style="color:#999;">Sin URL</span>
                    @endif
                  </td>
                  <td style="padding:12px; font-size:12px; color:#666;">
                    {{ $afiliacion->created_at->format('d/m/Y') }}
                  </td>
                  <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center;">
                      <button onclick="editarAfiliacion({{ $afiliacion->id }}, '{{ addslashes($afiliacion->titulo) }}', '{{ addslashes($afiliacion->descripcion) }}', '{{ $afiliacion->url }}')" 
                              style="padding:4px 8px; background:#ffc107; color:white; border:none; border-radius:4px; font-size:12px; cursor:pointer;">
                        ✏️ Editar
                      </button>
                      <form action="{{ route('admin.afiliaciones.destroy', $afiliacion->id) }}" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta afiliación?')">
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
          <h4 style="color:#666;">No hay afiliaciones registradas</h4>
          <p style="color:#999; margin-top:8px;">Crea la primera afiliación usando el formulario de arriba.</p>
        </div>
      @endif
    </div>

    {{-- Modal para editar --}}
    <div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:24px; border-radius:8px; width:90%; max-width:500px;">
        <h3 style="margin-bottom:16px;">✏️ Editar Afiliación</h3>
        <form id="editForm" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div>
            <label class="label">Título</label>
            <input id="editTitulo" name="titulo" type="text" class="mt-2 input" required>
          </div>
          <div class="mt-4">
            <label class="label">URL (opcional)</label>
            <input id="editUrl" name="url" type="url" class="mt-2 input" placeholder="https://...">
          </div>
          <div class="mt-4">
            <label class="label">Imagen (opcional)</label>
            <input id="editImagen" name="imagen" type="file" accept="image/*" class="mt-2 input">
          </div>
          <div class="mt-4">
            <label class="label">Descripción</label>
            <textarea id="editDescripcion" name="descripcion" rows="3" class="mt-2 textarea" required></textarea>
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
function editarAfiliacion(id, titulo, descripcion, url) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editForm').action = '{{ route("admin.afiliaciones.update", ":id") }}'.replace(':id', id);
    document.getElementById('editTitulo').value = titulo;
    document.getElementById('editDescripcion').value = descripcion;
    document.getElementById('editUrl').value = url || '';
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