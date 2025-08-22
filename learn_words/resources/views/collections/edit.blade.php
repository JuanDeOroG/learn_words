{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/collections/edit.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
    <style>
        .wrapper {
            width: 90%;
            max-width: 100vw;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid wrapper">
    <div class="row">
        {{-- Columna izquierda: Buscador de colecciones --}}
        <div class="col-lg-3 col-md-4 border-end" style="min-width:320px;">
            <div class="mb-3">
                <h5>Search Collections</h5>
                <input type="text" id="collectionSearch" class="form-control" placeholder="Search by name or description">
            </div>
            <div id="collectionsList" class="list-group mb-3"></div>
            <div class="d-flex justify-content-center">
                <button id="loadMoreBtn" class="btn btn-outline-primary btn-sm d-none">Load more</button>
            </div>
        </div>
        {{-- Columna derecha: Detalle de colección y palabras --}}
        <div class="col-lg-8 col-md-8">
            <div id="collectionDetail" class="mb-4 w-100"></div>
            <div id="wordsCard" class="w-100"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentPage = 1;
let currentQuery = '';
let selectedCollectionId = null;
let loading = false;

// Cargar colecciones iniciales
function loadCollections(page = 1, query = '') {
    if (loading) return;
    loading = true;
    fetch("{{ route('wordCollection.edit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ page, query })
    })
    .then(res => res.json())
    .then(data => {
        if (page === 1) {
            document.getElementById('collectionsList').innerHTML = '';
        }
        data.collections.forEach(collection => {
            const item = document.createElement('button');
            item.className = 'list-group-item list-group-item-action';
            item.textContent = collection.name;
            item.onclick = () => selectCollection(collection.id);
            document.getElementById('collectionsList').appendChild(item);
        });
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (data.hasMore) {
            loadMoreBtn.classList.remove('d-none');
            loadMoreBtn.onclick = () => loadCollections(++currentPage, currentQuery);
        } else {
            loadMoreBtn.classList.add('d-none');
        }
        loading = false;
    });
}

// Buscar colecciones
document.getElementById('collectionSearch').addEventListener('input', function() {
    currentQuery = this.value;
    currentPage = 1;
    loadCollections(currentPage, currentQuery);
});

// Seleccionar colección
function selectCollection(collectionId) {
    selectedCollectionId = collectionId;
    fetch("{{ route('wordCollection.edit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ collection_id: collectionId })
    })
    .then(res => res.json())
    .then(data => {
        // Card superior: datos de la colección en inputs editables
        document.getElementById('collectionDetail').innerHTML = `
            <form id="editCollectionForm">
                <div class="card mb-3" style="width:100%; height:28vh; min-height:100px;">
                    <div class="card-body d-flex flex-column justify-content-center py-2">
                        <div class="mb-2 d-flex align-items-center">
                            <label class="form-label me-2 mb-0" style="min-width:100px;">Name</label>
                            <input type="text" class="form-control form-control-sm" id="editCollectionName" value="${data.collection.name ?? ''}">
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <label class="form-label me-2 mb-0" style="min-width:100px;">Description</label>
                            <textarea class="form-control form-control-sm" id="editCollectionDescription" rows="1">${data.collection.description ?? ''}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Save Changes</button>
                    </div>
                </div>
            </form>
        `;
        // Card inferior: palabras de la colección
        let wordsHtml = `
            <div class="card" style="width:100%; height:55vh; min-height:200px; overflow-y:auto;">
                <div class="card-header">
                    <strong>Words in this collection</strong>
                </div>
                <ul class="list-group list-group-flush" id="wordsList">
        `;
        data.words.forEach(word => {
            wordsHtml += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <strong>${word.word}</strong>
                        <small class="text-muted ms-2">${word.translation ?? ''}</small>
                    </span>
                    <div>
                        <button class="btn btn-sm btn-outline-danger me-2" onclick="removeWord(${word.id})">Remove</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editWord(${word.id})">Edit</button>
                    </div>
                </li>
            `;
        });
        wordsHtml += `</ul></div>`;
        document.getElementById('wordsCard').innerHTML = wordsHtml;
    });
}

// Quitar palabra de la colección
function removeWord(wordId) {
    fetch("{{ route('wordCollection.edit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ collection_id: selectedCollectionId, remove_word_id: wordId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectCollection(selectedCollectionId);
        }
    });
}

// Editar palabra (modal simple)
function editWord(wordId) {
    fetch("{{ route('wordCollection.edit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ word_id: wordId, collection_id: selectedCollectionId, get_word: true })
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            title: 'Edit Word',
            html: `
                <input id="editWordInput" class="form-control mb-2" value="${data.word.word}">
                <input id="editTranslationInput" class="form-control" value="${data.word.translation ?? ''}" placeholder="Translation">
            `,
            showCancelButton: true,
            confirmButtonText: 'Save',
            preConfirm: () => {
                return {
                    word: document.getElementById('editWordInput').value,
                    translation: document.getElementById('editTranslationInput').value
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                fetch("{{ route('wordCollection.edit') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        word_id: wordId,
                        collection_id: selectedCollectionId,
                        update_word: true,
                        word: result.value.word,
                        translation: result.value.translation
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        selectCollection(selectedCollectionId);
                        Swal.fire('Saved!', '', 'success');
                    }
                });
            }
        });
    });
}

// Carga inicial
loadCollections();
</script>
@endsection
