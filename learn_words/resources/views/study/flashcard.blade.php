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
            // array para saber si ya se incrementó el contador de cada palabra
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

            document.getElementById('nextWord').onclick = async function() {
                // sólo incrementar si no se ha incrementado antes para esta palabra
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