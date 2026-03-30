@extends('layouts.app')
@section('title','Inicio — JUGA Martial Arts')
@section('content')

{{-- Sección principal de bienvenida --}}
<section class="section" style="background-image: url('{{ asset('images/fondojuga1.jpeg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container">
        <div class="hero" style="display:flex; flex-direction:column; align-items:center; text-align:center; padding:60px 20px; gap:24px;">
            <div style="background: rgba(255,255,255,0.85); padding:40px 30px; border-radius:12px; max-width:700px;">
                <h1 class="h1" style="font-size:3rem; font-weight:700; color:#000;">JUGA MARTIAL ARTS</h1>
                <p class="mt-4 lead" style="font-size:1.8rem; font-weight:600; color:#000;">Forma parte de la escuela</p>
                <p class="mt-3 muted" style="font-size:1.2rem; line-height:1.6; color:#000;">
                    En JUGA Martial Arts enseñamos porque creemos que las artes marciales transforman vidas: mejoran la salud, fortalecen el carácter y crean una comunidad donde cada alumno crece y se siente parte de algo más grande.
                </p>
                <div style="display:flex; justify-content:center; gap:12px; margin-top:20px;">
                    <a href="{{ route('inscripciones') }}" class="btn btn-primary" style="padding:10px 20px;">Inscribirme</a>
                    <a href="{{ route('horarios') }}" class="btn" style="padding:10px 20px;">Ver horarios</a>
                    
                    {{-- Botón especial para admin --}}
                    @auth
                        @if(auth()->user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com'))
                            <a href="{{ route('admin.inscripciones') }}" 
                               class="btn" 
                               style="background:#ff9800;color:white;padding:10px 20px;border:none;">
                                📊 Panel Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div style="margin-top:30px; width:100%; display:flex; justify-content:center;">
                <img src="{{ asset('images/hero-juga.jpg') }}" alt="clases de artes marciales" class="hero-img" onerror="this.style.display='none'" style="max-width:80%; border-radius:12px;">
            </div>
        </div>
    </div>
</section>

{{-- Sección del Profesor --}}
<section class="section" style="
    background-image: url('{{ asset('images/fondo4.png') }}');
    background-size: cover;
    background-position: top center;
    background-repeat: no-repeat;
    padding: 80px 20px;
">
    <div class="container" style="display:flex; justify-content:center;">
        <div style="background: rgba(0,0,0,0.5); padding:40px 30px; border-radius:12px; max-width:700px; color:#fff; text-align:center;">
            <div class="label" style="font-weight:700; font-size:1.5rem; color:#808080;">Profesor</div>
            <div class="mt-1" style="font-weight:700; font-size:1.8rem; margin-top:8px;">
                <span style="color:#c62828;">Juan</span> 
                <span style="color:#2e7d32;">Gabriel</span> 
                <span style="color:#fbc02d;">Garnica</span>
            </div>
            <p class="mt-2" style="font-size:1.2rem; line-height:1.6; margin-top:12px;">
                Conoce al profe Juan Gabriel Garnica Iriarte, cinturón negro en Jiu Jitsu con más de 12 años de experiencia y una vida dedicada a las artes marciales. Ha entrenado con grandes referentes internacionales y hoy dirige la filial oficial de Tom DeBlass en Bolivia, además de ser head coach de las fuerzas especiales de Cochabamba. Su verdadera pasión es formar a las nuevas generaciones, transmitiendo disciplina, respeto, confianza y resiliencia. En JUGA Martial Arts inspira a cada alumno a construir su mejor versión.
            </p>
        </div>
    </div>
</section>

{{-- Sección de texto Jiu-Jitsu con fondo azul medio oscuro --}}
<section class="section" style="background-color: #1565c0; width: 100%; padding: 80px 20px;">
    <div class="container" style="max-width:700px; margin:0 auto; text-align:center; color:#fff;">
        <div class="label" style="font-weight:700; font-size:1.5rem; color:#000;">Jiu-Jitsu</div>
        <div class="mt-1" style="font-weight:700; font-size:1.8rem; margin-top:8px; color:#fbc02d;">
            Porque es bueno en los niños
        </div>
        <p class="mt-2" style="font-size:1.2rem; line-height:1.6; margin-top:12px;">
            El Jiu Jitsu es una de las mejores actividades para los niños, porque no solo mejora su condición física, sino que también fortalece su carácter y valores. 
            A través del entrenamiento aprenden disciplina, respeto y autocontrol, desarrollan confianza en sí mismos, mejoran su concentración y adquieren herramientas para manejar mejor sus emociones. 
            Además, les brinda defensa personal de forma segura y fomenta la amistad y el compañerismo en un ambiente positivo. En JUGA Martial Arts ayudamos a que los niños crezcan fuertes, seguros y con valores que les servirán toda la vida.
        </p>
    </div>
</section>

{{-- Sección solo con imagen de fondo --}}
<section class="section" style="
    background-image: url('{{ asset('images/fondoninos.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100vh; /* ocupa toda la altura de la pantalla */
">
</section>

@endsection