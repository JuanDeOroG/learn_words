{{-- filepath: resources/views/evaluateConjugations/fill.blade.php --}}
@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{asset('css/evaluateConjugations/fill.css')}}">
@endsection

@section('content')
<div class="fill-eval-container">
    <h2>Completa las formas verbales</h2>
    <form method="POST" action="{{ route('evaluateConjugations.fill.check') }}" autocomplete="off" id="fill-form">
        @csrf
        <input type="hidden" name="group_key" value="{{ $groupKey }}">
        <div class="table-responsive">
            <table class="fill-table">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($columns as $col)
                            <td>
                                @if(in_array($col, $toFill))
                                    <input type="text" name="answers[{{ $col }}]" class="fill-input" autocomplete="off" style="text-align: center;"
                                        value="{{ old('answers.'.$col) }}"
                                        @if(session('feedback') && session('feedback')[$col]['correct']) style="background:#e8fce8" @endif
                                        @if(session('feedback') && !session('feedback')[$col]['correct']) style="background:#ffeaea" @endif
                                    >
                                    @if(session('feedback') && !session('feedback')[$col]['correct'])
                                        <div class="correct-answer">Correcto: {{ $wordForms[$col] }}</div>
                                    @endif
                                @else
                                    <span class="given-form">{{ $wordForms[$col] }}</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="fill-actions">
            <button type="submit" class="btn-primary">Comprobar</button>
            <a href="{{ route('evaluateConjugations.fill') }}" class="btn-secondary" style="display:none;" id="next-btn">Siguiente</a>
        </div>
        <div id="feedback-message"></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('fill-form');
    const nextBtn = document.getElementById('next-btn');
    const feedbackDiv = document.getElementById('feedback-message');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Limpiar feedback anterior
        document.querySelectorAll('.correct-answer').forEach(el => el.remove());
        document.querySelectorAll('.fill-input').forEach(input => {
            input.style.background = '';
        });
        if (nextBtn) nextBtn.style.display = 'none';
        if (feedbackDiv) feedbackDiv.innerHTML = '';

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Marcar inputs según feedback
            Object.entries(data.feedback).forEach(([conj, info]) => {
                const input = form.querySelector(`[name="answers[${conj}]"]`);
                if (input) {
                    input.style.background = info.correct ? '#e3fce8' : '#ffeaea';
                    if (!info.correct) {
                        const div = document.createElement('div');
                        div.className = 'correct-answer';
                        div.innerText = 'Correcto: ' + info.correct_answer;
                        input.parentNode.appendChild(div);
                    }
                }
            });
            // Mostrar mensaje y botón siguiente si todo es correcto
            if (data.allCorrect) {
                if (feedbackDiv) feedbackDiv.innerHTML = '<span style="color:#1976d2;font-weight:bold;">¡Todo correcto! Puedes avanzar.</span>';
                if (nextBtn) nextBtn.style.display = 'inline-block';
            }
        });
    });
});
</script>
@endsection