@extends('layouts.app')
@section('title','Panel Admin — Deportes')

@section('content')
<section class="section" style="background-image: url('{{ asset('images/03-fondo.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <div class="container" style="max-width:1200px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <h2 class="h2">Panel Administrativo - Deportes</h2>
    
    {{-- Navegación del admin --}}
    @include('admin.partials.admin-nav')

    @if (session('status'))
       <div class="mt-4" style="color:#0a7d1c; padding:12px; background:#e8f5e8; border-radius:4px;">
         {{ session('status') }}
       </div>
    @endif

    {{-- Formulario de creación --}}
    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">➕ Nuevo Deporte</h3>
      <form action="{{ route('admin.deportes.store') }}" method="post">
        @csrf
        <div class="grid grid-2" style="gap:20px;">
          <div>
            <label class="label">Nombre</label>
            <input name="nombre" type="text" class="mt-2 input" value="{{ old('nombre') }}" placeholder="Ej: Fútbol, Básquetbol" required>
            @error('nombre')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">Descripción (opcional)</label>
            <textarea name="descripcion" rows="1" class="mt-2 textarea" placeholder="Información adicional...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Crear Deporte</button>
        </div>
      </form>
    </div>

    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">Deportes Registrados ({{ $deportes->count() }})</h3>
      
      @if($deportes->count() > 0)
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f5f5f5; border-bottom:2px solid #ddd;">
                <th style="padding:12px; text-align:left;">ID</th>
                <th style="padding:12px; text-align:left;">Nombre</th>
                <th style="padding:12px; text-align:left;">Descripción</th>
                <th style="padding:12px; text-align:left;">Fecha Creación</th>
                <th style="padding:12px; text-align:center;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($deportes as $deporte)
                <tr style="border-bottom:1px solid #eee;">
                  <td style="padding:12px;"><strong>{{ $deporte->id }}</strong></td>
                  <td style="padding:12px;">
                    <strong>{{ $deporte->nombre }}</strong>
                  </td>
                  <td style="padding:12px; max-width:400px;">
                    {{ $deporte->descripcion ?? '—' }}
                  </td>
                  <td style="padding:12px; font-size:12px; color:#666;">
                    {{ $deporte->created_at->format('d/m/Y') }}
                  </td>
                  <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center;">
                      <button onclick="editarDeporte({{ $deporte->id }}, '{{ addslashes($deporte->nombre) }}', '{{ addslashes($deporte->descripcion ?? '') }}')"
                              style="padding:4px 8px; background:#ffc107; color:white; border:none; border-radius:4px; font-size:12px; cursor:pointer;">
                        ✏️ Editar
                      </button>
                      <form action="{{ route('admin.deportes.destroy', $deporte->id) }}" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este deporte?')">
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
          <h4 style="color:#666;">No hay deportes registrados</h4>
          <p style="color:#999; margin-top:8px;">Los deportes se crean automáticamente con los seeders.</p>
        </div>
      @endif
    </div>

    {{-- Modal para editar --}}
    <div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:24px; border-radius:8px; width:90%; max-width:500px;">
        <h3 style="margin-bottom:16px;">✏️ Editar Deporte</h3>
        <form id="editForm" method="post">
          @csrf
          @method('PUT')
          <div style="margin-bottom:16px;">
            <label class="label">Nombre</label>
            <input id="editNombre" name="nombre" type="text" class="mt-2 input" required>
          </div>
          <div style="margin-bottom:16px;">
            <label class="label">Descripción (opcional)</label>
            <textarea id="editDescripcion" name="descripcion" rows="3" class="mt-2 textarea"></textarea>
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
function editarDeporte(id, nombre, descripcion) {
  document.getElementById('editModal').style.display = 'block';
  document.getElementById('editForm').action = '{{ route("admin.deportes.update", ":id") }}'.replace(':id', id);
  document.getElementById('editNombre').value = nombre;
  document.getElementById('editDescripcion').value = descripcion || '';
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