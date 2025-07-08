{{-- filepath: resources/views/evaluateConjugations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card eval-card" onclick="location.href='{{ route('evaluateConjugations.fill') }}'">
        <div class="eval-icon">
            <!-- Icono lápiz -->
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path fill="#4CAF50" d="M3 17.25V21h3.75l11.06-11.06-3.75-3.75L3 17.25zm14.71-9.04a1.003 1.003 0 0 0 0-1.42l-2.5-2.5a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
        </div>
        <h2>Llenar campos</h2>
        <p>Completa las formas que faltan.</p>
    </div>
    <div class="card eval-card" onclick="location.href='{{ url('/evaluateConjugations/order') }}'">
        <div class="eval-icon">
            <!-- Icono lista ordenada -->
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path fill="#4CAF50" d="M7 7h14v2H7V7zm0 4h14v2H7v-2zm0 4h14v2H7v-2zM3.5 8.5l1.5 1.5-1.5 1.5-1.5-1.5 1.5-1.5zm0 4l1.5 1.5-1.5 1.5-1.5-1.5 1.5-1.5zm0 4l1.5 1.5-1.5 1.5-1.5-1.5 1.5-1.5z"/></svg>
        </div>
        <h2>Ordenar las formas</h2>
        <p>Arrastra las formas verbales en el orden correcto.</p>
    </div>
    <div class="card eval-card" onclick="location.href='{{ url('/evaluateConjugations/choice') }}'">
        <div class="eval-icon">
            <!-- Icono checkboxes -->
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" fill="#4CAF50"/><path fill="#fff" d="M7 13l3 3 7-7-1.41-1.42L10 13.17l-1.59-1.59L7 13z"/></svg>
        </div>
        <h2>Selección múltiple</h2>
        <p>Elige la forma correcta entre varias opciones.</p>
    </div>
</div>
@endsection