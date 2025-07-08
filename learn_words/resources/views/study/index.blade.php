{{-- filepath: resources/views/study/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card" onclick="location.href='{{ route('study.index') }}'">
        <h2>Study All Words</h2>
        <p>Practice all words and choose which verb form you want to study (e.g., infinitive, past, etc.).</p>
    </div>
    <div class="card" onclick="location.href='{{ route('study.index') }}'">
        <h2>Study by Collections</h2>
        <p>Study words grouped in collections. Collections can represent topics, difficulty, or any custom grouping.</p>
    </div>
</div>
@endsection