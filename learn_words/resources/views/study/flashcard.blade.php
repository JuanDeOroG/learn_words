<div id="flashcard-container" class="w-100 d-flex justify-content-center">
    {{-- palabra actual --}}
</div>
<div class="mt-4 d-flex justify-content-center gap-2">
    <button id="prevWord" class="btn btn-secondary" disabled>Previous</button>
    <button id="nextWord" class="btn btn-primary">Next</button>
</div>
<div class="mt-2 text-center">
    <span id="wordCounter"></span>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        @if ($mode === 'flashcard')
            const words = @json($words);
            let current = 0;
            let studied = Array(words.length).fill(false);

            async function incrementStudyCount(wordId) {
                try {
                    await fetch("{{ route('study.incrementStudyCount') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ word_id: wordId })
                    });
                } catch (error) {
                    console.error("Error incrementing study count:", error);
                }
            }

            function speakWord(text) {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'en-US'; // puedes cambiar el idioma
                    utterance.rate = 0.7; // velocidad (1 es normal, <1 más lento, >1 más rápido)
                    window.speechSynthesis.speak(utterance);
                } else {
                    alert('Speech Synthesis not supported in this browser.');
                }
            }

            function renderWord(idx) {
                const word = words[idx];
                let imageUrl = `https://source.unsplash.com/320x180/?${encodeURIComponent(word.word)}`;
                let html = `
                    <div class="card shadow" style="max-width: 500px; min-width: 350px; min-height: 420px;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title mb-3">${word.word}</h4>
                            <img src="${imageUrl}" alt="Image" class="img-fluid mb-3" style="max-width:320px; max-height:180px;">
                            <button class="btn btn-outline-primary mb-2" id="speakWordBtn">🔊 Pronounce</button>
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

                // añadir evento al botón de pronunciar
                document.getElementById('speakWordBtn').onclick = function() {
                    speakWord(word.word);
                };
            }

            document.getElementById('prevWord').onclick = function() {
                if (current > 0) {
                    current--;
                    renderWord(current);
                }
            };

            document.getElementById('nextWord').onclick = async function() {
                if (!studied[current]) {
                    await incrementStudyCount(words[current].id);
                    studied[current] = true;
                }
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