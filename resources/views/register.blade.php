<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro — ArtesMar</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}"> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRuvzKMRqM+ORnBvRFL6DOitFpri4fjHxaWutUpFM8P4vMVor" crossorigin="anonymous">
</head>
<body>
  <main class="container align-center p-5">
    
    <div class="text-center mb-4">
      <img src="{{ asset('images/logo-juga.png') }}" alt="Logo ArtesMar" style="height:80px;" onerror="this.style.display='none'">
      <h2 class="mt-3">Crear Cuenta</h2>
      <p class="text-muted">Regístrate para poder realizar tu inscripción</p>
    </div>

    @if (session('status'))
       <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
       <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('validar-registro') }}">
      @csrf

      <div class="mb-3">
        <label for="userInput" class="form-label">Nombre Completo</label>
        <input type="text" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios" class="form-control @error('name') is-invalid @enderror" 
               id="userInput" name="name" value="{{ old('name') }}" required 
               placeholder="Ej: Juan Pérez García">
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Debe incluir nombre y apellido, cada uno iniciando con mayúscula.</div>
      </div>

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
        <div class="form-text">Mínimo 6 caracteres.</div>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
      </div>

      <div class="text-center">
        <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
        <p><a href="{{ route('home') }}">← Volver al inicio</a></p>
      </div>
    </form>
  </main>
  <script>
    (function(){
      const nameInput = document.getElementById('userInput');
      if (!nameInput) return;

      // Evitar que se peguen o escriban dígitos
      nameInput.addEventListener('input', function(e){
        const original = this.value;
        const filtered = original.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]+/g, '');
        if (original !== filtered) {
          this.value = filtered;
        }
        // validación visual
        if (/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,}$/.test(this.value.trim())) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        } else {
          this.classList.remove('is-valid');
          // no marcar inválido hasta el submit, pero mantenemos la clase si ya hay error del servidor
        }
      });

      // Validar antes de enviar
      const form = nameInput.form;
      if (form) {
        form.addEventListener('submit', function(e){
          const val = nameInput.value.trim();
          if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,}$/.test(val) || val.split(/\s+/).length < 2) {
            e.preventDefault();
            nameInput.classList.add('is-invalid');
            nameInput.focus();
            // Mostrar feedback si no existe
            let fb = nameInput.parentNode.querySelector('.invalid-feedback');
            if (!fb) {
              fb = document.createElement('div');
              fb.className = 'invalid-feedback';
              nameInput.parentNode.appendChild(fb);
            }
            fb.textContent = 'Ingrese nombre y apellido válidos (solo letras).';
          }
        });
      }
    })();
  </script>
</body>
</html>
