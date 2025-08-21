{{-- filepath: resources/views/study/session.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
    <link rel="stylesheet" href="{{ asset('css/study/session.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">

            {{-- contenido principal centrado --}}
            <div class="col-lg-9 col-md-8 d-flex flex-column align-items-center justify-content-center mx-auto">
                @if ($mode === 'flashcard')
                    @include('study.flashcard', ['words' => $words, 'mode' => $mode])
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
                                @foreach ($words as $word)
                                    <tr>
                                        <td>{{ $word->word }}</td>
                                        <td>
                                            <img src="https://source.unsplash.com/80x60/?{{ urlencode($word->word) }}"
                                                alt="Image" class="img-fluid">
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
                        @foreach ($words as $word)
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

    <x-study.config-view :collections="$collections" :goal="$goal" :order="$order" :mode="$mode" />
@endsection
