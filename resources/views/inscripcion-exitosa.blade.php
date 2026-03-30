@extends('layouts.app')
@section('title','Inscripción Exitosa — ArtesMar')

@section('content')
<section class="section">
  <div class="container" style="max-width:720px">

    <div class="center" style="margin-bottom:12px">
      <img src="{{ asset('images/logo-juga.png') }}" alt="logo juga" style="height:60px"
           onerror="this.style.display='none'">
    </div>

    <div class="card" style="text-align:center; padding:40px;">
      <h2 class="h2" style="color:#0a7d1c;">¡Inscripción Exitosa!</h2>
      <p class="mt-4" style="font-size:18px;">Tu inscripción ha sido registrada correctamente.</p>
      
      <div style="background:#f8f9fa; padding:20px; border-radius:8px; margin:24px 0;">
        <h3 style="color:#333; margin-bottom:16px;">📧 Datos de tu Inscripción</h3>
        <div style="text-align:left; max-width:450px; margin:0 auto;">
          <p><strong>Nombre:</strong> {{ $inscripcion->nombre }}</p>
          <p><strong>Email:</strong> {{ $inscripcion->email }}</p>
          <p><strong>Teléfono:</strong> {{ $inscripcion->telefono ?: 'No proporcionado' }}</p>
          <p><strong>Disciplina:</strong> {{ $inscripcion->disciplina->nombre ?? 'N/A' }}</p>
          <p><strong>Horario:</strong> {{ $inscripcion->categoria }} - {{ $inscripcion->dias }} - {{ $inscripcion->hora }}</p>
          @if($inscripcion->sucursal)
            <p><strong>Sucursal:</strong> {{ $inscripcion->sucursal }}</p>
          @endif
        </div>
        
        {{-- Recordatorio de precios --}}
        <div style="margin-top:20px; padding:16px; background:#e3f2fd; border-radius:8px;">
          <h4 style="margin:0 0 12px; color:#1565c0; font-size:16px;">💰 Recordatorio de Precios</h4>
          <div style="display:flex; justify-content:center; gap:15px; flex-wrap:wrap;">
            <div style="background:white; padding:8px 12px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
              <span style="font-weight:600; color:#1565c0;">5 días: 350 Bs</span>
            </div>
            <div style="background:white; padding:8px 12px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
              <span style="font-weight:600; color:#1565c0;">3 días: 280 Bs</span>
            </div>
            <div style="background:white; padding:8px 12px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
              <span style="font-weight:600; color:#1565c0;">2 días: 250 Bs</span>
            </div>
          </div>
          <p style="margin:8px 0 0; font-size:13px; color:#1565c0; text-align:center;">
            Paga según la frecuencia semanal que prefieras
          </p>
        </div>
      </div>

      <div style="background:#e8f5e8; padding:24px; border-radius:8px; margin:24px 0;">
        <h3 style="color:#0a7d1c; margin-bottom:16px;">💳 Completa tu Pago</h3>
        <p style="margin-bottom:20px; font-size:16px;">Escanea el código QR con tu app bancaria para realizar el pago:</p>
        
        <div style="display:inline-block; padding:20px; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
          <img src="{{ asset('images/qr-artes-mar.jpg') }}" 
               alt="Código QR para pago - Mercantil Santa Cruz" 
               style="max-width:280px; width:100%; height:auto; border-radius:8px;"
               onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjI4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMjgwIiBoZWlnaHQ9IjI4MCIgZmlsbD0iI2YwZjBmMCIvPgogIDx0ZXh0IHg9IjE0MCIgeT0iMTQwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiM2NjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5RUiBubyBkaXNwb25pYmxlPC90ZXh0Pgo8L3N2Zz4K';">
        </div>
        
        <div style="margin-top:20px; padding:16px; background:#f8f9fa; border-radius:8px; border-left:4px solid #28a745;">
          <h4 style="margin:0 0 12px; color:#155724; font-size:16px;">🏦 Información de Pago</h4>
          <div style="text-align:left; font-size:14px; color:#155724;">
            <p style="margin:4px 0;"><strong>🏛️ Banco:</strong> Mercantil Santa Cruz</p>
            <p style="margin:4px 0;"><strong>💰 Concepto:</strong> Escuela de Artes Marciales</p>
            <p style="margin:4px 0;"><strong>📅 Vigencia:</strong> Hasta 31/12/2025</p>
            <p style="margin:8px 0 0;"><strong>📱 Apps compatibles:</strong> Mercantil, Tigo Money, Simple, BCP Móvil, etc.</p>
          </div>
        </div>
        
        <div style="margin-top:16px; padding:12px; background:#fff3cd; border-radius:6px;">
          <p style="margin:0; font-size:13px; color:#856404;">
            💡 <strong>Tip:</strong> Abre tu app bancaria → Escanear QR → Confirma el monto según tu plan elegido
          </p>
        </div>
      </div>

      {{-- Mensajes de éxito --}}
      @if(session('success'))
        <div style="background:#d4edda; padding:16px; border-radius:8px; margin:24px 0; border-left:4px solid #28a745;">
          <p style="margin:0; color:#155724; font-weight:500;">
            ✅ {{ session('success') }}
          </p>
          {{-- Mensaje de éxito simple: no usamos WhatsApp automático --}}
        </div>
      @endif

      {{-- Subida de comprobante --}}
      <div style="background:#e3f2fd; padding:24px; border-radius:8px; margin:24px 0; border-left:4px solid #2196f3;">
        <h4 style="color:#1565c0; margin-bottom:16px;">📷 Sube tu Comprobante de Pago</h4>
        
        <form action="{{ route('inscripciones.comprobante', $inscripcion->id) }}" method="post" enctype="multipart/form-data" id="comprobanteForm">
          @csrf
          <div style="text-align:left;">
            <label for="comprobante" style="display:block; margin-bottom:8px; font-weight:500; color:#1565c0;">
              Selecciona tu comprobante (JPG, PNG o PDF):
            </label>
            <input type="file" 
                   id="comprobante" 
                   name="comprobante" 
                   accept="image/*,.pdf" 
                   required
                   style="width:100%; padding:12px; border:2px dashed #2196f3; border-radius:8px; background:white; margin-bottom:12px;"
                   onchange="previewFile(this)">
            
            <div id="filePreview" style="display:none; margin:16px 0; text-align:center;">
              <div id="imageContainer" style="display:none;">
                <img id="preview" src="" alt="Vista previa" style="max-width:300px; max-height:200px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <p style="margin:8px 0 0; font-size:12px; color:#666;">Vista previa de tu imagen</p>
              </div>
              <div id="pdfContainer" style="display:none; padding:16px; background:#f5f5f5; border-radius:8px;">
                <div style="font-size:48px; color:#d32f2f;">📄</div>
                <p id="pdfName" style="margin:8px 0 0; font-size:14px; color:#666; font-weight:500;"></p>
                <p style="margin:4px 0 0; font-size:12px; color:#999;">Archivo PDF seleccionado</p>
              </div>
            </div>
            
            <button type="submit" 
                    style="width:100%; padding:12px; background:#2196f3; color:white; border:none; border-radius:6px; font-weight:500; font-size:16px; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='#1976d2'"
                    onmouseout="this.style.background='#2196f3'">
              📤 Enviar Comprobante
            </button>
          </div>
        </form>
        
        <div style="margin:16px 0 0; padding:12px; background:#e8f5e8; border-radius:6px; border-left:4px solid #4caf50;">
          <p style="margin:0; font-size:13px; color:#2e7d32; text-align:center;">
            <strong>📱 ¿Cómo funciona?</strong><br>
            1️⃣ Sube tu comprobante (JPG, PNG o PDF)<br>
            2️⃣ El comprobante se enviará a nuestro equipo para verificación<br>
            3️⃣ Nuestro equipo te contactará por WhatsApp o email en las próximas 24 horas
          </p>
        </div>
      </div>

      <div style="background:#fff3cd; padding:20px; border-radius:8px; margin:24px 0; border-left:4px solid #ffc107;">
        <h4 style="color:#856404; margin-bottom:12px;">📞 Próximos Pasos</h4>
        <div style="text-align:left; font-size:14px; color:#856404;">
          <ul style="margin:0; padding-left:20px;">
            <li>Completa el pago usando el código QR</li>
            <li><strong>Sube tu comprobante de pago usando el formulario de arriba</strong></li>
            <li>Nuestro equipo se pondrá en contacto contigo en las próximas 24 horas</li>
            <li>Recibirás la confirmación de tu horario y detalles adicionales</li>
            <li>Si tienes dudas, contáctanos por WhatsApp o email</li>
          </ul>
        </div>
      </div>

      <div class="mt-6">
        <a href="{{ route('home') }}" class="btn btn-primary" style="margin-right:12px;">Volver al Inicio</a>
        <a href="{{ route('logout') }}" class="btn btn-secondary">Cerrar Sesión</a>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
function previewFile(input) {
    const filePreview = document.getElementById('filePreview');
    const imageContainer = document.getElementById('imageContainer');
    const pdfContainer = document.getElementById('pdfContainer');
    const preview = document.getElementById('preview');
    const pdfName = document.getElementById('pdfName');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type;
        
        // Ocultar todos los contenedores primero
        imageContainer.style.display = 'none';
        pdfContainer.style.display = 'none';
        
        if (fileType.startsWith('image/')) {
            // Es una imagen
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imageContainer.style.display = 'block';
                filePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            // Es un PDF
            pdfName.textContent = file.name;
            pdfContainer.style.display = 'block';
            filePreview.style.display = 'block';
        }
    } else {
        filePreview.style.display = 'none';
    }
}

// No hay integración automática de WhatsApp en el cliente
</script>
@endpush
@endsection