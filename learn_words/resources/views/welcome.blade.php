@extends('layouts.app', ['hideBackButton' => true])

@section('content')
    <div class="card-container">
        <div class="card" onclick="location.href='{{route('study.index')}}'">
            <h2>Study</h2>
            <p>Access a variety of words to study.</p>
        </div>
        <div class="card" onclick="location.href='{{ route('evaluate.index') }}'">
            <h2>Evaluate</h2>
            <p>Test your knowledge and see how much you've learned.</p>
        </div>
        <div class="card" onclick="location.href='{{ route('collections.index') }}'">
            <h2>Word Collections</h2>
            <p>Explore different collections for targeted learning.</p>
        </div>
        <div class="card" onclick="location.href='{{ url('/settings') }}'">
            <h2>Settings</h2>
            <p>Customize your learning experience and preferences.</p>
        </div>
    </div>
@endsection