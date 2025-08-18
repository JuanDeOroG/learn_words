{{-- filepath: resources/views/study/session.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
    <link rel="stylesheet" href="{{ asset('css/study/session.css') }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">

        {{-- Contenido principal centrado --}}
        <div class="col-lg-9 col-md-8 d-flex flex-column align-items-center justify-content-center mx-auto">
            @if($mode === 'flashcard')
                <div id="flashcard-container" class="w-100 d-flex justify-content-center">
                    {{-- Aquí se mostrará la palabra actual --}}
                </div>
                <div class="mt-4 d-flex justify-content-center gap-2">
                    <button id="prevWord" class="btn btn-secondary" disabled>Previous</button>
                    <button id="nextWord" class="btn btn-primary">Next</button>
                </div>
                <div class="mt-2 text-center">
                    <span id="wordCounter"></span>
                </div>
            @elseif($mode === 'table')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Word</th>
                                <th>Image</th>
                                <th>Audio</th>
                                <th>Example</th>
                                <th>Translation</th>
                                <th>Conjugation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($words as $word)
                                <tr>
                                    <td>{{ $word->word }}</td>
                                    <td>
                                        <img src="https://source.unsplash.com/80x60/?{{ urlencode($word->word) }}" alt="Image" class="img-fluid">
                                    </td>
                                    <td>
                                        <audio controls style="width:120px;">
                                            <source src="{{ $word->audio_url ?? '#' }}" type="audio/mpeg">
                                        </audio>
                                    </td>
                                    <td>{{ $word->example ?? 'No example available.' }}</td>
                                    <td>{{ $word->translation ?? '-' }}</td>
                                    <td>{{ $word->conjugation->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($mode === 'audio')
                <div class="row">
                    @foreach($words as $word)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="card-title">{{ $word->word }}</h4>
                                    <audio controls style="width:120px;">
                                        <source src="{{ $word->audio_url ?? '#' }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 text-center" id="evaluateBtn" style="display:none;">
                <a href="{{ route('evaluate.index') }}" class="btn btn-success">Evaluate Now</a>
            </div>
        </div>
    </div>
</div>

<x-study.config-view 
    :collections="$collections" 
    :goal="$goal" 
    :order="$order" 
    :mode="$mode" 
/>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar/ocultar panel de Session Settings sin mover el card
        const toggleBtn = document.getElementById('toggleSessionSettings');
        const hideBtn = document.getElementById('hideSessionSettings');
        const panel = document.getElementById('sessionSettingsPanel');
        if (toggleBtn && panel) {
            toggleBtn.onclick = function() {
                panel.style.display = '';
            };
        }
        if (hideBtn && panel) {
            hideBtn.onclick = function() {
                panel.style.display = 'none';
            };
        }

        @if($mode === 'flashcard')
        const words = @json($words);
        let current = 0;

        function renderWord(idx) {
            const word = words[idx];
            let html = `
                <div class="card shadow" style="max-width: 500px; min-width: 350px; min-height: 420px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h4 class="card-title mb-3">${word.word}</h4>
                        <img src="https://source.unsplash.com/320x180/?${encodeURIComponent(word.word)}" alt="Image" class="img-fluid mb-3" style="max-width:320px; max-height:180px;">
                        <audio controls class="mb-3" style="width:220px;">
                            <source src="${word.audio_url ?? '#'}" type="audio/mpeg">
                        </audio>
                        <p class="card-text mb-2"><em>${word.example ?? 'No example available.'}</em></p>
                        ${word.translation ? `<p class="text-muted mb-2">Translation: ${word.translation}</p>` : ''}
                        ${word.conjugation ? `<div class="mt-2"><strong>Conjugation:</strong> <span class="badge bg-info">${word.conjugation.name}</span></div>` : ''}
                    </div>
                </div>
            `;
            document.getElementById('flashcard-container').innerHTML = html;
            document.getElementById('wordCounter').textContent = `Word ${idx+1} of ${words.length}`;
            document.getElementById('prevWord').disabled = idx === 0;
            document.getElementById('nextWord').disabled = idx === words.length - 1;
            document.getElementById('evaluateBtn').style.display = (idx === words.length - 1) ? '' : 'none';
        }

        document.getElementById('prevWord').onclick = function() {
            if (current > 0) {
                current--;
                renderWord(current);
            }
        };
        document.getElementById('nextWord').onclick = function() {
            if (current < words.length - 1) {
                current++;
                renderWord(current);
            }
        };

        renderWord(current);
        @endif
    });
</script>
@endsection