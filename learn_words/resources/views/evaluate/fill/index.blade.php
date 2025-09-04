{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/evaluate/fill/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Fill Evaluation Configuration</h2>
    <form method="GET" action="{{ route('evaluate.fill.session') }}">
        {{-- Selección de modo: aleatorio o colecciones específicas --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Word Source</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="word_source" id="randomWordsRadio" value="random" checked>
                <label class="form-check-label" for="randomWordsRadio">
                    Random Words
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="word_source" id="chooseCollectionsRadio" value="collections" data-bs-toggle="modal" data-bs-target="#chooseCollectionModal">
                <label class="form-check-label" for="chooseCollectionsRadio" data-bs-toggle="modal" data-bs-target="#chooseCollectionModal">
                    Choose Collection(s)
                </label>
                <input type="button" class="btn btn-warning btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#chooseCollectionModal" value="View Collections">
            </div>
        </div>

        {{-- Modal para elegir colecciones --}}
        @include('components.study.collection-selection')

        {{-- Tipo de ejercicio --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Evaluation Mode</label>
            <select class="form-select" name="evaluation_mode" id="evaluation_mode">
                <option value="imageAndText">Image and Text</option>
                <option value="image">Image Only</option>
                <option value="audio">Audio Only</option>
                {{-- <option value="example">Write an Example</option> --}}
                <option value="all">All</option>
            </select>
        </div>

        {{-- Dirección de evaluación --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Direction</label>
            <select class="form-select" name="direction" id="direction">
                <option value="en_to_es">English to Spanish</option>
                <option value="es_to_en">Spanish to English</option>
            </select>
        </div>

        {{-- Cantidad de palabras a evaluar --}}
        <div class="mb-4">
            <label class="form-label fw-bold" for="words_count">Number of Words</label>
            <input type="number" class="form-control" name="words_count" id="words_count" min="1" max="100" value="20">
        </div>

        <button type="submit" class="btn btn-primary">Start Evaluation</button>
    </form>
</div>
@endsection