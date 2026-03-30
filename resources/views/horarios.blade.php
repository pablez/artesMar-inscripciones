@extends('layouts.app')
@section('title','Horarios — JUGA Martial Arts')
@section('content')
<section class="section" style="
  background-image: url('{{ asset('images/04-fondo.jpg') }}');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh; 
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
">
  <div class="container" style="
      background: rgba(255,255,255,0.85);
      padding: 40px 30px;
      border-radius: 12px;
      max-width: 900px;
      width: 100%;
  ">
    <h2 class="h2" style="text-align:center; margin-bottom:12px;">Horarios de la academia</h2>
    <p class="mt-2 muted" style="text-align:center;">Planifica tus entrenamientos y elige el horario que mejor se adapte a tu nivel y disponibilidad.</p>

    <div class="mt-8" style="overflow:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Día</th><th>Disciplina</th><th>Categoria</th><th>Horario</th><th>Sucursal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($horarios as $h)
            <tr>
              <td>{{ $h->dias }}</td>
              <td>{{ $h->disciplina ? $h->disciplina->nombre : $h->titulo }}</td>
              <td>{{ $h->categoria }}</td>
              <td>{{ $h->hora }}</td>
              <td>{{ $h->sucursal }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection