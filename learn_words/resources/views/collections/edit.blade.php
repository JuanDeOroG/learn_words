{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/collections/edit.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
@include('components.wordcollection.edit-word-modal')
@endsection

@section('scripts')
<script>
window.searchImageUrl = "{{ route('searchImage') }}";
wordCollectionEditUrl = "{{ route('wordCollection.edit') }}";
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
            item.onclick = () => {
                // Si ya está seleccionada, no hacer nada
                if (selectedCollectionId === collection.id) return;
                selectCollection(collection.id);
            };
            // Si está seleccionada, deshabilitar el botón
            if (selectedCollectionId === collection.id) {
                item.disabled = true;
                item.classList.add('active');
            }
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
                        <div class="row mt-2">
                            <div class="col-12 pe-1">
                                <button type="button" class="btn btn-dark btn-sm w-100">Save Changes</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        `;
        // Card inferior: palabras de la colección
        // console.log(data);
        let wordsHtml = `
            <div class="card" style="width:100%; height:55vh; min-height:200px; overflow-y:auto;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Words in this collection (${data.words.length})</span>
                    <div class="d-flex" style="gap: 8px;">
                        <a href="{{ route('wordCollection.add') }}?collection_id=${data.collection.id}" class="btn btn-dark btn-sm" id="addWordBtn">Add Word</a>
                        <button type="button" class="btn btn-warning btn-sm" id="importWordBtn">Import</button>
                    </div>
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
                        <button class="btn btn-sm btn-outline-secondary" onclick="editWord(${word.id}, this)">Edit</button>
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
    Swal.fire({
        title: 'Are you sure?',
        text: 'This word will be removed from the collection.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('wordCollection.edit') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    collection_id: selectedCollectionId,
                    remove_word_id: wordId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    selectCollection(selectedCollectionId);
                    Swal.fire('Removed!', 'The word has been removed.', 'success');
                }
            });
        }
    });
}


// Carga inicial
loadCollections();
</script>

<script src="{{ asset('js/edit-searchimg.js') }}"></script>
@endsection
