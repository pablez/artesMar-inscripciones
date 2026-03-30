{{-- Barra de navegación del admin --}}
<div style="background:#f8f9fa;border-radius:8px;padding:16px;margin:20px 0;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <span style="background:#ff9800;color:white;padding:6px 12px;border-radius:4px;font-size:12px;font-weight:500;">
        👑 Administrador: {{ auth()->user()->name }}
      </span>
      <span style="color:#666;font-size:14px;">Panel de Control</span>
    </div>
    
    <div style="display:flex;align-items:center;gap:8px;">
      <a href="{{ route('home') }}" class="btn" style="padding:6px 12px;font-size:12px;">🏠 Inicio</a>
      <a href="{{ route('logout') }}" 
         class="btn" 
         style="background:#f44336;color:white;padding:6px 12px;font-size:12px;"
         onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
        Cerrar Sesión
      </a>
      <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>
  </div>

  <nav style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="{{ route('admin.inscripciones') }}" 
       class="btn {{ request()->routeIs('admin.inscripciones') ? 'btn-primary' : '' }}" 
       style="font-size:12px;padding:8px 12px;">
      📋 Inscripciones
    </a>
    <a href="{{ route('admin.disciplinas') }}" 
       class="btn {{ request()->routeIs('admin.disciplinas') ? 'btn-primary' : '' }}" 
       style="font-size:12px;padding:8px 12px;">
      🥋 Disciplinas
    </a>
    <a href="{{ route('admin.afiliaciones') }}" 
       class="btn {{ request()->routeIs('admin.afiliaciones') ? 'btn-primary' : '' }}" 
       style="font-size:12px;padding:8px 12px;">
      📄 Afiliaciones
    </a>
    <a href="{{ route('admin.horarios') }}" 
       class="btn {{ request()->routeIs('admin.horarios') ? 'btn-primary' : '' }}" 
       style="font-size:12px;padding:8px 12px;">
      ⏰ Horarios
    </a>
    <a href="{{ route('admin.deportes') }}" 
       class="btn {{ request()->routeIs('admin.deportes') ? 'btn-primary' : '' }}" 
       style="font-size:12px;padding:8px 12px;">
      🏆 Deportes
    </a>
  </nav>
</div>