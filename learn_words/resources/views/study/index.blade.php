{{-- filepath: resources/views/study/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Opciones de configuración</h2>
        <form method="GET" action="{{route('study.session')}}">
            {{-- Selección de colecciones --}}
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="randomWords" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Random words
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="randomWords" checked data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" id="chooseCollectionRadio">
                    <label class="form-check-label" id="" data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" onclick="chooseCollectionChecked()">
                        Choose Collection/s
                    </label>
                    <input type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#chooseCollectionModal" id="chooseCollectionRadio" value="view">
                </div>
                @include('components.study.collection-selection')
            </div>


            {{-- Objetivo de la sesión (opcional) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Session goal</label>
                <select class="form-select mb-2" name="goal_type" id="goal_type">
                    <option value="">No goal</option>
                    <option value="time">By time</option>
                    <option value="quantity">By number of words</option>
                </select>

            </div>

            {{-- Cantidad de palabras a estudiar --}}
            <div class="mb-4">
                <label class="form-label fw-bold" for="words_count"></label>
                <input type="number" class="form-control" name="words_count" id="words_count" min="1" max="100"
                    value="20">
                <input type="number" class="form-control" name="words_time" id="words_time"
                    placeholder="E.g.: 10 minutes" style="display:none;">
            </div>

            {{-- Orden de estudio --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Study order</label>
                <select class="form-select" name="order">
                    <option value="least_studied">Least studied first</option>
                    <option value="random">Random</option>
                    <option value="recent">Most recent</option>
                    <option value="older">Oldest</option>
                </select>
            </div>

            {{-- Modo de presentación (opcional) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Presentation mode (optional)</label>
                <select class="form-select" name="presentation_mode">
                    <option value="flashcard">Cards with text, image and audio</option>
                    <option value="table">Image only</option>
                    <option value="audio">Audio only</option>
                </select>
            </div>



            <button type="submit" class="btn btn-primary">Start study</button>
        </form>
    </div>


    <script>
        function chooseCollectionChecked() {
            console.log('chooseCollectionChecked');
            document.getElementById('chooseCollectionRadio').checked = true;
        }
        // show/hide and update label/input according to selected goal
        document.addEventListener('DOMContentLoaded', function() {
            const goalType = document.getElementById('goal_type');
            const goalValue = document.getElementById('words_time');
            const wordsDiv = document.getElementById('words_count').parentElement;
            const wordsLabel = wordsDiv.querySelector('label');

            function updateGoalFields() {
                let val = goalType.value;
                wordsDiv.style.display = '';
                goalValue.style.display = 'none';
                if (val === "") {
                    // hide word count field and goal input
                    wordsDiv.style.display = 'none';
                    goalValue.style.display = 'none';
                } else if (val === "time") {
                    // show input for minutes and change label
                    wordsLabel.textContent = "Choose time in minutes";
                } else if (val === "quantity") {
                    // show input for number of words and change label
                    wordsLabel.textContent = "Choose number of words";
                }
            }

            goalType.addEventListener('change', updateGoalFields);
            updateGoalFields();
        });

        // filter collections by name
        function filterCollections() {
            let input = document.getElementById('search-collection').value.toLowerCase();
            let checks = document.querySelectorAll('#collections-list .form-check');
            checks.forEach(function(check) {
                let checkbox = check.querySelector('input[type="checkbox"]');
                let label = check.textContent.toLowerCase();
                // if selected, always show
                if (checkbox.checked) {
                    check.style.display = '';
                } else {
                    check.style.display = label.includes(input) ? '' : 'none';
                }
            });
        }
    </script>
@endsection