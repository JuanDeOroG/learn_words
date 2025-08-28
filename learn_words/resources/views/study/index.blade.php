{{-- filepath: resources/views/study/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Configuration Options</h2>
        <form method="GET" action="{{route('study.session')}}">
            {{-- selección de colecciones --}}
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="randomWordsOption" id="flexRadioDefault1" value="yes" checked>
                    <label class="form-check-label" for="flexRadioDefault1">
                        Random Words
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="randomWordsOption" data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" id="chooseCollectionRadio" value="no">
                    <label class="form-check-label" data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" onclick="chooseCollectionChecked()">
                        Choose Collection(s)
                    </label>
                    <input type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" id="chooseCollectionRadio" value="View">
                </div>
                @include('components.study.collection-selection')
            </div>

            {{-- objetivo de la sesión (opcional) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Session Goal</label>
                <select class="form-select mb-2" name="goal_type" id="goal_type">
                    <option value="no">No goal (Press the button to finish)</option>
                    <option value="time">By Time</option>
                    <option value="quantity">By Number of Words</option>
                </select>
            </div>

            {{-- cantidad de palabras a estudiar --}}
            <div class="mb-4">
                <label class="form-label fw-bold" for="words_count"></label>
                <input type="number" class="form-control" name="words_count" id="words_count" min="1" max="100"
                    value="20">
                <input type="number" class="form-control" name="words_time" id="words_time"
                    placeholder="E.g.: 10 minutes" style="display:none;">
            </div>

            {{-- orden de estudio --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Study Order</label>
                <select class="form-select" name="order">
                    <option value="least_studied">Least Studied First</option>
                    <option value="random">Random</option>
                    <option value="recent">Most Recent</option>
                    <option value="older">Oldest</option>
                </select>
            </div>

            {{-- modo de presentación (opcional) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Presentation Mode (Optional)</label>
                <select class="form-select" name="presentation_mode">
                    <option value="flashcard">Cards with Text, Image and Audio</option>
                    <option value="table">Image Only</option>
                    <option value="audio">Audio Only</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Start Study</button>
        </form>
    </div>

    <script>
        // marcar el radio de colección cuando se hace clic en el label
        function chooseCollectionChecked() {
            // console.log('chooseCollectionChecked');
            document.getElementById('chooseCollectionRadio').checked = true;
        }
        // mostrar/ocultar y actualizar el label/input según el objetivo seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const goalType = document.getElementById('goal_type');
            const goalValue = document.getElementById('words_time');
            const wordsDiv = document.getElementById('words_count').parentElement;
            const wordsLabel = wordsDiv.querySelector('label');

            function updateGoalFields() {
                let val = goalType.value;
                wordsDiv.style.display = '';
                goalValue.style.display = 'none';
                if (val === "no") {
                    // ocultar campo de cantidad de palabras y el input de objetivo
                    wordsDiv.style.display = 'none';
                    goalValue.style.display = 'none';
                } else if (val === "time") {
                    // mostrar input para minutos y cambiar el label
                    wordsLabel.textContent = "Choose Time in Minutes";
                } else if (val === "quantity") {
                    // mostrar input para cantidad de palabras y cambiar el label
                    wordsLabel.textContent = "Choose Number of Words";
                }
            }

            goalType.addEventListener('change', updateGoalFields);
            updateGoalFields();
        });
    </script>
@endsection