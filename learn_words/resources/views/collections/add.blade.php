{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/collections/add.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .wrapper {
            width: 80%;
            max-width: 100vw;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <h4>Add Word to Collection: {{ $collection->name }}</h4>
    <form id="addWordForm" class="mb-4">
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="word" id="wordInput" class="form-control" placeholder="Word" required>
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="translation" id="translationInput" class="form-control" placeholder="Translation">
            </div>
            <div class="col-md-6 mb-2">
                <input type="hidden" name="image_url" id="imageUrlInput">
                <button type="button" class="btn btn-dark w-100" id="searchImageBtn">Search Image</button>
            </div>
        </div>
        <div id="imageResults" class="row my-2"></div>
        <button type="submit" class="btn btn-primary mt-2">Add Word</button>
    </form>

    <div class="card mt-4 w-100">
        <div class="card-header">Words in this Collection</div>
        <div class="card-body p-2">
            <div class="row row-cols-1 row-cols-md-3 g-2">
                @foreach($words as $word)
                    <div class="col">
                        <div class="list-group-item d-flex flex-column align-items-start" style="min-height:80px;">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $word->word }}</strong>
                                    <small class="text-muted ms-2">{{ $word->translation }}</small>
                                </div>
                                <div class="d-flex" style="gap:4px;">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="editWord({{ $word->id }}, this)">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeWord({{ $word->id }})">Remove</button>
                                </div>
                            </div>
                            @if($word->image_url)
                                <img src="{{ $word->image_url }}" alt="img" style="height:40px; width:100%; object-fit:cover; margin-top:4px;">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@include('components.wordcollection.edit-word-modal')

@endsection

@section('scripts')
<script>
window.searchImageUrl = "{{ route('searchImage') }}";
wordCollectionEditUrl = "{{ route('wordCollection.edit') }}";

document.getElementById('addWordForm').onsubmit = function(e) {
    e.preventDefault();
    const data = {
        word: document.getElementById('wordInput').value,
        translation: document.getElementById('translationInput').value,
        image_url: document.getElementById('imageUrlInput').value,
        collection_id: {{ $collection->id }}
    };
    fetch("{{ route('wordCollection.add') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        if (res.ok) {
            const result = await res.json();
            if (result.success) {
                location.reload();
            }
        } else if (res.status === 422) {
            const error = await res.json();
            let messages = '';
            Object.values(error.errors).forEach(arr => {
                messages += arr.join('<br>');
            });
            Swal.fire('Validation error', messages, 'error');
        }
    });
}

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
                    collection_id: {{ $collection->id }},
                    remove_word_id: wordId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Removed!', 'The word has been removed.', 'success').then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

document.getElementById('searchImageBtn').onclick = function() {
        const query = document.getElementById('wordInput').value;
        if (!query) return;
        fetch(window.searchImageUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query: query, perPage: 15 })
        })
        .then(res => res.json())
        .then(data => {
            let html = '<div class="d-flex flex-wrap justify-content-start" style="gap:10px;">';
            // data.images debe ser un array de URLs
            data.images.forEach(url => {
                html += `
                    <div style="width:120px; height:120px; display:flex; align-items:center; justify-content:center; border-radius:8px; overflow:hidden; background:#f8f9fa;">
                        <img src="${url}" class="img-thumbnail select-image" style="width:120px; height:120px; object-fit:cover; cursor:pointer;" onclick="selectImage('${url}')">
                    </div>
                `;
            });
            html += '</div>';
            document.getElementById('imageResults').innerHTML = html;
        });
    };

</script>
<script src="{{ asset('js/edit-searchimg.js') }}"></script>

@endsection