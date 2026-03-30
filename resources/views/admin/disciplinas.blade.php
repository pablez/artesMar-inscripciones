@extends('layouts.app')
@section('title','Panel Admin — Disciplinas')

@section('content')
<section class="section">
  <div class="container" style="max-width:1200px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <h2 class="h2">Panel Administrativo - Disciplinas</h2>
    
    {{-- Navegación del admin --}}
    @include('admin.partials.admin-nav')

    @if (session('status'))
       <div class="mt-4" style="color:#0a7d1c; padding:12px; background:#e8f5e8; border-radius:4px;">
         {{ session('status') }}
       </div>
    @endif

    {{-- Formulario de creación --}}
    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">➕ Nueva Disciplina</h3>
      <form action="{{ route('admin.disciplinas.store') }}" method="post">
        @csrf
        <div class="grid grid-2" style="gap:20px;">
          <div>
            <label class="label">Nombre</label>
            <input name="nombre" type="text" class="mt-2 input" value="{{ old('nombre') }}" placeholder="Ej: Jiu Jitsu Brasileño" required>
            @error('nombre')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="label">¿Activo?</label>
            <div style="margin-top:8px; display:flex; align-items:center; gap:8px;">
              <input type="checkbox" name="activo" value="1" {{ old('activo') ? 'checked' : '' }} id="activeCheckbox" style="width:18px; height:18px; cursor:pointer;">
              <label for="activeCheckbox" style="margin:0; cursor:pointer; font-size:14px;">Habilitar esta disciplina inmediatamente</label>
            </div>
          </div>
          <div style="grid-column:1/-1;">
            <label class="label">Descripción (opcional)</label>
            <textarea name="descripcion" rows="2" class="mt-2 textarea" placeholder="Información adicional sobre la disciplina...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
              <div class="mt-1" style="color:#b00020">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Crear Disciplina</button>
        </div>
      </form>
    </div>

    <div class="card mt-6">
      <h3 style="margin-bottom:16px;">Disciplinas Registradas ({{ $disciplinas->count() }})</h3>
      
      @if($disciplinas->count() > 0)
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f5f5f5; border-bottom:2px solid #ddd;">
                <th style="padding:12px; text-align:left;">ID</th>
                <th style="padding:12px; text-align:left;">Nombre y Descripción</th>
                <th style="padding:12px; text-align:left;">Inscripciones</th>
                <th style="padding:12px; text-align:left;">Fecha Creación</th>
                <th style="padding:12px; text-align:left;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($disciplinas as $disciplina)
                <tr style="border-bottom:1px solid #eee;">
                  <td style="padding:12px;"><strong>{{ $disciplina->id }}</strong></td>
                  <td style="padding:12px;">
                    <form action="{{ route('admin.disciplinas.update', $disciplina->id) }}" method="POST" id="form-disc-{{ $disciplina->id }}">
                      @csrf
                      @method('PUT')
                      <div style="display:flex; gap:12px; align-items:flex-start;">
                        <!-- Inputs de nombre y descripción -->
                        <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                          <input 
                            name="nombre" 
                            value="{{ $disciplina->nombre }}" 
                            required 
                            style="padding:8px; font-weight:bold; border:1px solid #ddd; border-radius:4px;" 
                            placeholder="Nombre de disciplina" />
                          <textarea 
                            name="descripcion" 
                            style="padding:8px; font-size:12px; resize:vertical; min-height:45px; border:1px solid #ddd; border-radius:4px;" 
                            placeholder="Descripción...">{{ $disciplina->descripcion ?? '' }}</textarea>
                        </div>
                        
                        <!-- Estado (checkbox + badge) -->
                        <div style="display:flex; flex-direction:column; gap:8px; align-items:center; padding-top:2px;">
                          <label title="Activar/Desactivar" style="display:flex; align-items:center; gap:6px; cursor:pointer; background:#f5f5f5; padding:8px 12px; border-radius:4px; border:1px solid #ddd; transition: all 0.2s ease;">
                            <!-- Input hidden para asegurar que se envía el valor 0 si no está checked -->
                            <input type="hidden" name="activo" value="0">
                            <input 
                              type="checkbox" 
                              name="activo" 
                              value="1"
                              {{ $disciplina->activo ? 'checked' : '' }} 
                              style="width:18px; height:18px; cursor:pointer;" />
                            <span style="font-size:12px; font-weight:500; color:#333;">
                              {{ $disciplina->activo ? '✓ Activo' : '✗ Inactivo' }}
                            </span>
                          </label>
                          @if(!$disciplina->activo)
                            <small style="color:#ff9800; font-weight:bold; text-align:center; font-size:11px;">
                              (No visible)
                            </small>
                          @endif
                        </div>
                      </div>
                    </form>
                  </td>
                  <td style="padding:12px;">
                    <span style="background:#e3f2fd; color:#1976d2; padding:6px 10px; border-radius:4px; font-size:12px; font-weight:500;">
                      {{ $disciplina->inscripciones_count }} inscripciones
                    </span>
                  </td>
                  <td style="padding:12px; font-size:12px; color:#666;">
                    {{ $disciplina->created_at->format('d/m/Y') }}
                  </td>
                  <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                      <button 
                        class="btn btn-primary" 
                        type="submit" 
                        form="form-disc-{{ $disciplina->id }}" 
                        style="padding:6px 16px; font-size:12px;">
                        💾 Guardar
                      </button>
                      <form 
                        action="{{ route('admin.disciplinas.destroy', $disciplina->id) }}" 
                        method="POST" 
                        style="display:inline;" 
                        onsubmit="return confirm('¿Estás seguro de eliminar &quot;{{ $disciplina->nombre }}&quot;?')">
                        @csrf
                        @method('DELETE')
                        <button 
                          class="btn" 
                          type="submit"
                          style="background:#f44336; color:white; border:none; padding:6px 16px; border-radius:4px; cursor:pointer; font-size:12px;">
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
          <h4 style="color:#666;">No hay disciplinas registradas</h4>
          <p style="color:#999; margin-top:8px;">Usa el formulario de arriba para crear la primera disciplina.</p>
        </div>
      @endif
    </div>

  </div>
</section>
@endsection