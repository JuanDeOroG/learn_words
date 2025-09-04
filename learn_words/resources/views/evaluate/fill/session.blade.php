{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/evaluate/fill/session.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4 d-flex justify-content-center">
    <div class="d-flex flex-column align-items-center w-100" style="max-width:600px;">
        <div id="fill-eval-card-container" class="w-100">
            {{-- Aquí se renderiza la tarjeta actual --}}
        </div>
        <div class="mt-4 d-flex justify-content-center gap-2 w-100">
            <button id="prevWord" class="btn btn-secondary" disabled>Previous</button>
            <button id="nextWord" class="btn btn-primary">Next</button>
            <button id="submitEvalBtn" class="btn btn-success" style="display:none;">Submit Evaluation</button>
        </div>
        <div class="mt-2 text-center w-100">
            <span id="wordCounter"></span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const words = @json($words);
    const config = @json($config);
    let current = 0;
    let answers = Array(words.length).fill('');
    
    function speakWord(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = config.direction === 'en_to_es' ? 'en-US' : 'es-ES';
            utterance.rate = 0.8;
            window.speechSynthesis.speak(utterance);
        } else {
            alert('Speech Synthesis not supported in this browser.');
        }
    }

    function renderWord(idx) {
        const word = words[idx];
        let mode = config.evaluation_mode;

        // Mostrar imagen si el modo lo requiere
        let showImage = ['image', 'imageAndText', 'all'].includes(mode) && word.image_url;
        // Mostrar texto si el modo lo requiere
        let showText = ['imageAndText', 'all', 'example'].includes(mode);
        // Mostrar audio si el modo lo requiere
        let showAudio = ['audio', 'all'].includes(mode);
        // Mostrar input para ejemplo si el modo lo requiere
        let showExample = mode === 'example';
        // Mostrar input normal si no es ejemplo
        let showInput = !showExample;

        let questionText = config.direction === 'en_to_es' ? word.word : word.translation;
        let pronunciation = word.pronuntiation ?? '';

        let html = `
            <div class="card shadow" style="max-width: 600px; min-width: 350px; min-height: 320px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    ${showImage ? `<img src="${word.image_url}" alt="Image" class="img-fluid mb-3" style="max-width:320px; max-height:180px;">` : ''}
                    ${showAudio ? `<button class="btn btn-outline-info mb-2" id="speakWordBtn">🔊 Listen</button>` : ''}
                    ${showText ? `<div class="mb-2 fw-bold" style="font-size:1.2em;">${questionText}</div>` : ''}
                    ${pronunciation ? `<div class="mb-2 text-muted" style="font-size:1em;">Pronunciation: <span class="fst-italic">${pronunciation}</span></div>` : ''}
                    ${showExample 
                        ? `<input type="text" class="form-control" id="userInput" placeholder="Write an example using this word" value="${answers[idx] ?? ''}">`
                        : `<input type="text" class="form-control" id="userInput" placeholder="Your answer" value="${answers[idx] ?? ''}">`
                    }
                </div>
            </div>
        `;
        document.getElementById('fill-eval-card-container').innerHTML = html;
        document.getElementById('wordCounter').textContent = `Word ${idx+1} of ${words.length}`;
        document.getElementById('prevWord').disabled = idx === 0;
        document.getElementById('nextWord').disabled = idx === words.length - 1;
        document.getElementById('submitEvalBtn').style.display = (idx === words.length - 1) ? '' : 'none';

        // Guardar respuesta al cambiar input
        document.getElementById('userInput').oninput = function() {
            answers[idx] = this.value;
        };

        // Evento para pronunciar
        if (showAudio && document.getElementById('speakWordBtn')) {
            document.getElementById('speakWordBtn').onclick = function() {
                speakWord(questionText);
            };
        }
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

    document.getElementById('submitEvalBtn').onclick = function() {
        // Calcular correctas e incorrectas (ejemplo simple, puedes mejorar la lógica)
        let correct = [];
        let incorrect = [];
        let score = 0;

        for (let i = 0; i < words.length; i++) {
            // Ejemplo: compara con la traducción si es en_to_es, o con la palabra si es es_to_en
            let expected = config.direction === 'en_to_es' ? words[i].translation : words[i].word;
            if (answers[i] && answers[i].trim().toLowerCase() === (expected ?? '').trim().toLowerCase()) {
                correct.push({id: words[i].id, word: words[i].word, answer: answers[i]});
                score++;
            } else {
                incorrect.push({id: words[i].id, word: words[i].word, answer: answers[i], expected: expected});
            }
        }

        fetch("{{ route('evaluate.fill.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                type: 'fill',
                config: config,
                score: score,
                total: words.length,
                correct: correct,
                incorrect: incorrect,
                started_at: null, // Puedes guardar el tiempo si lo deseas
                finished_at: new Date().toISOString()
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let correctRows = correct.map(item => `
                    <tr>
                        <td>${item.word}</td>
                        <td><span class="badge bg-success">${item.answer}</span></td>
                    </tr>
                `).join('');
                let incorrectRows = incorrect.map(item => `
                    <tr>
                        <td>${item.word}</td>
                        <td><span class="badge bg-danger">${item.answer || '-'}</span></td>
                        <td><span class="badge bg-secondary">Expected: ${item.expected}</span></td>
                    </tr>
                `).join('');

                Swal.fire({
                    title: 'Evaluation Results',
                    html: `
                        <div style="text-align:left">
                            <b>Score:</b> ${score} / ${words.length}<br><br>
                            <b>Correct Answers (${correct.length}):</b>
                            <table class="table table-sm table-bordered mb-3">
                                <thead>
                                    <tr>
                                        <th>Word</th>
                                        <th>Your Answer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${correctRows || '<tr><td colspan="2">None</td></tr>'}
                                </tbody>
                            </table>
                            <b>Incorrect Answers (${incorrect.length}):</b>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Word</th>
                                        <th>Your Answer</th>
                                        <th>Expected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${incorrectRows || '<tr><td colspan="3">None</td></tr>'}
                                </tbody>
                            </table>
                        </div>
                    `,
                    icon: 'info',
                    width: 700
                });
            }
        });
    };

    renderWord(current);
});
</script>
@endsection