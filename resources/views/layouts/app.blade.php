<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'JUGA Martial Arts')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('head')
</head>
<body>
<div class="wrapper">
  <header class="header">
    <div class="container header-inner" style="display:flex;align-items:center;justify-content:space-between;gap:20px;padding:0 20px;">
      
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="brand" style="display:flex;align-items:center;gap:12px;">
        <img src="{{ asset('images/logo-juga.png') }}" alt="JUGA logo" style="height:50px" onerror="this.style.display='none'">
        <span style="font-size:18px;font-weight:700;letter-spacing:0.5px;">JUGA MARTIAL ARTS</span>
      </a>

      {{-- Menú principal --}}
      @php
        $links = [
          ['name' => 'Inicio', 'route' => 'home'],
          ['name' => 'Horarios', 'route' => 'horarios'],
          ['name' => 'Deportes', 'route' => 'artes'],
          ['name' => 'Afiliaciones', 'route' => 'afiliaciones'],
          ['name' => 'Ubicación', 'route' => 'ubicacion'],
          ['name' => 'Inscripciones', 'route' => 'inscripciones'],
        ];
      @endphp

      <div style="display:flex;align-items:center;gap:16px;flex:1;justify-content:space-between;max-width:100%;">
        
        {{-- Navegación central --}}
        <nav class="nav" style="display:flex;align-items:center;gap:4px;margin-left:20px;">
          @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" 
               class="{{ request()->routeIs($link['route']) ? 'active' : '' }}"
               style="padding:8px 12px;font-size:14px;font-weight:500;border-radius:6px;transition:all 0.2s ease;white-space:nowrap;">
              {{ $link['name'] }}
            </a>
          @endforeach
        </nav>

        {{-- Sección derecha: Redes sociales + Autenticación --}}
        <div style="display:flex;align-items:center;gap:12px;">
          
          {{-- Redes sociales --}}
          <div style="display:flex;align-items:center;gap:8px;padding-right:12px;border-right:1px solid #e0e0e0;">
            {{-- Instagram --}}
            <a href="https://www.instagram.com/jugabjj/" target="_blank" title="Síguenos en Instagram" 
               style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D, #F56040, #F77737, #FCAF45, #FFDC80);transition:all 0.3s ease;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
              <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            
            {{-- Facebook --}}
            <a href="https://www.facebook.com/jugamartialarts/?locale=es_LA" target="_blank" title="Síguenos en Facebook"
               style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#1877F2;transition:all 0.3s ease;box-shadow:0 2px 8px rgba(24, 119, 242, 0.3);">
              <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
          </div>
          
          {{-- Sección de autenticación --}}
          @auth
            <div class="auth-section" style="display:flex;align-items:center;gap:8px;flex-wrap:nowrap;">
              {{-- Nombre del usuario (solo primera palabra) --}}
              <span class="user-name" style="font-size:13px;font-weight:500;color:#333;white-space:nowrap;">
                Hola, <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong>
              </span>
              
              {{-- Botón del panel admin (solo para admin) --}}
              @if(auth()->user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com'))
                <a href="{{ route('admin.inscripciones') }}" class="admin-btn" style="display:flex;align-items:center;gap:3px;font-size:12px;padding:6px 10px;white-space:nowrap;">
                  📋 Admin
                </a>
              @endif
              
              {{-- Botón cerrar sesión --}}
              <a href="{{ route('logout') }}" 
                 class="logout-btn"
                 style="display:flex;align-items:center;gap:3px;font-size:12px;padding:6px 10px;white-space:nowrap;"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Salir
              </a>
              
              {{-- Formulario hidden para logout --}}
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          @else
            {{-- Botones para usuarios no autenticados --}}
            <div class="auth-section" style="display:flex;align-items:center;gap:8px;">
              <a href="{{ route('login') }}" class="btn-login" style="padding:6px 14px;font-size:13px;white-space:nowrap;">Iniciar Sesión</a>
              <a href="{{ route('registro') }}" class="register-btn" style="padding:6px 14px;font-size:13px;white-space:nowrap;">
                Registro
              </a>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </header>

  <main style="flex:1 1 auto">
    @yield('content')
  </main>

  <footer class="footer">
    <div class="container section footer-grid">
      <div>
        <div style="font-weight:700">JUGA MARTIAL ARTS</div>
        <p class="mt-2 muted">En JUGA Martial Arts entrenamos más que el cuerpo: cultivamos la mente y el carácter. A través del Jiu Jitsu, Boxeo y Kickboxing, nuestros alumnos desarrollan disciplina, confianza y respeto, en una comunidad que inspira a cada persona a alcanzar su mejor versión.</p>
      </div>
      <div>
        <div style="font-weight:700">Enlaces</div>
        <ul class="mt-2" style="list-style:none;padding:0;margin:0;display:grid;gap:6px">
          @foreach ($links as $link)
            <li><a href="{{ route($link['route']) }}">{{ $link['name'] }}</a></li>
          @endforeach
        </ul>
      </div>
      <div>
        <div style="font-weight:700">Contacto</div>
        <p class="mt-2 muted">Cochabamba, Bolivia</p>
        <p class="muted">Tel: <span class="mono">(+591) 69454445</span></p>
      </div>
    </div>
    <div class="copy">© {{ date('Y') }} JUGA MARTIAL ARTS. todos los derechos reservados.</div>
  </footer>
</div>

@stack('scripts')
</body>
</html>