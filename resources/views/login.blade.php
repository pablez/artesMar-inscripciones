<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión — ArtesMar</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}"> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRuvzKMRqM+ORnBvRFL6DOitFpri4fjHxaWutUpFM8P4vMVor" crossorigin="anonymous">
</head>
<body>
  <main class="container align-center p-5">
    
    <div class="text-center mb-4">
      <img src="{{ asset('images/logo-juga.png') }}" alt="Logo ArtesMar" style="height:80px;" onerror="this.style.display='none'">
      <h2 class="mt-3">Iniciar Sesión</h2>
    </div>

    @if (session('status'))
       <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
       <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('inicia-sesion') }}">
      @csrf

      <div class="mb-3">
        <label for="emailInput" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="emailInput" name="email" value="{{ old('email') }}" required>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="passwordInput" class="form-label">Contraseña</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="passwordInput" name="password" required>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="rememberCheck" name="remember">
        <label class="form-check-label" for="rememberCheck">Mantener sesión iniciada</label>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
      </div>

      <div class="text-center">
        <p>¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a></p>
        <p><a href="{{ route('home') }}">← Volver al inicio</a></p>
      </div>
    </form>

    @auth
      <div class="alert alert-info mt-4">
        <h5>¡Bienvenido {{ auth()->user()->name }}!</h5>
        <p>Ya tienes una sesión activa.</p>
        
        @if(auth()->user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com'))
          <div class="d-grid gap-2">
            <a href="{{ route('admin.inscripciones') }}" class="btn btn-warning">
              📋 Ver Inscripciones de Alumnos (Panel Admin)
            </a>
            <a href="{{ route('logout') }}" class="btn btn-secondary">Cerrar Sesión</a>
          </div>
        @else
          <div class="d-grid gap-2">
            <a href="{{ route('inscripciones') }}" class="btn btn-primary">Completar Inscripción</a>
            <a href="{{ route('logout') }}" class="btn btn-secondary">Cerrar Sesión</a>
          </div>
        @endif
      </div>
    @endauth

  </main>
</body>
</html>
