@extends('layouts.app')
@section('title','Artes Marciales — JUGA Martial Arts')
@section('content')
<section class="section" style="background-image: url('{{ asset(' ') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <div class="container" style="padding: 40px 20px;">
    <h2 class="h2">Artes Marciales</h2>
    <p class="mt-2 muted">Descubre nuestras disciplinas y entrena Jiu Jitsu, Boxeo y Kickboxing para fortalecer cuerpo, mente y carácter.</p>

    <div class="mt-8 grid grid-3">
      @foreach ([['title'=>'Jiu-Jitsu','desc'=>'El Jiu Jitsu Brasileño es un arte marcial centrado en la técnica, el control y la estrategia, más que en la fuerza. Desarrolla disciplina, confianza y autocontrol, además de brindar defensa personal efectiva. Para niños y adultos, es una herramienta poderosa para crecer física y mentalmente.'],
                 ['title'=>'Boxeo','desc'=>'El Boxeo es un deporte de combate que mejora la resistencia, la coordinación y la agilidad. A través de técnicas de puños, esquivas y movimientos de pies, no solo enseña defensa personal, sino también disciplina, autocontrol y fortaleza mental.'],
                 ['title'=>'Kickboxing','desc'=>'El Kickboxing combina técnicas de puños y patadas, ofreciendo un entrenamiento dinámico y completo. Mejora la fuerza, la flexibilidad y la velocidad, al mismo tiempo que enseña a mantener el enfoque, la disciplina y el respeto hacia los demás.']] as $d)
        <article class="card" style="background: rgba(255,255,255,0.92); padding: 0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s ease;">
          <img src="{{ asset('images/' . Str::slug($d['title']) . '.jpg') }}"
               alt="{{ $d['title'] }}"
               style="width:100%;height:220px;object-fit:cover;border-radius:0;margin-bottom:0"
               onerror="this.style.display='none'">
          <div style="padding: 16px;">
            <h3 style="font-weight:700;font-size:20px;margin-bottom:8px;color:#222;">{{ $d['title'] }}</h3>
            <p class="muted" style="font-size:14px;line-height:1.5;">{{ $d['desc'] }}</p>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endsection
