@extends('layouts.app')
@section('title','Afiliaciones — JUGA Martial Arts')
@section('content')
<section class="section">
  <div class="container">
    <h2 class="h2">Afiliaciones</h2>
    <p class="mt-2 muted">Somos parte de organizaciones y federaciones reconocidas a nivel mundial, lo que respalda nuestra calidad y compromiso.</p>

    <section class="section">
      <div class="container">
        <div class="mt-16 grid" style="grid-template-columns: repeat(3, 1fr); gap:20px; justify-items:center;">
          @foreach($afiliaciones as $item)
            <article class="card" style="text-align:center;">
              <h3 style="margin-bottom:10px; font-weight:700;">{{ $item->titulo }}</h3>
              @if($item->url)
                <a href="{{ $item->url }}" target="_blank">
              @endif
                @if($item->imagen)
                  <img src="{{ asset('storage/'.$item->imagen) }}" alt="{{ $item->titulo }}" style="max-width:100%; border-radius:12px;">
                @else
                  <div style="width:100%;height:140px;background:#f5f5f5;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#999;">Sin imagen</div>
                @endif
              @if($item->url)
                </a>
              @endif
            </article>
          @endforeach
        </div>
      </div>
    </section>

    <div class="mt-8" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:24px;place-items:center">
      @foreach($afiliaciones->take(8) as $logo)
        @if($logo->imagen)
          <img src="{{ asset('storage/'.$logo->imagen) }}" alt="logo afiliación {{ $logo->id }}" style="height:56px;object-fit:contain;opacity:.85" onerror="this.style.visibility='hidden'">
        @endif
      @endforeach
    </div>
  </div>
</section>
@endsection
