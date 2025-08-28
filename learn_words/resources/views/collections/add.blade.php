{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/collections/add.blade.php --}}
@extends('layouts.app', ['hideHeader' => true])

@section('head')
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
            {{-- <div class="col-md-4 mb-2">
                <input type="text" name="translation" id="translationInput" class="form-control" placeholder="Translation">
            </div> --}}
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
                                    {{-- <small class="text-muted ms-2">{{ $word->translation }}</small> --}}
                                </div>
                                <div class="d-flex" style="gap:4px;">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="editWord({{ $word->id }})">Edit</button>
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
@endsection

@section('scripts')
<script>
document.getElementById('searchImageBtn').onclick = function() {
    const query = document.getElementById('wordInput').value;
    if (!query) return;
    fetch("{{ route('searchImage') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
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

function selectImage(url) {
    document.getElementById('imageUrlInput').value = url;
    document.querySelectorAll('.select-image').forEach(img => img.classList.remove('border-success'));
    document.querySelectorAll(`img[src="${url}"]`).forEach(img => img.classList.add('border-success'));
}

document.getElementById('addWordForm').onsubmit = function(e) {
    e.preventDefault();
    const data = {
        word: document.getElementById('wordInput').value,
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
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            location.reload();
        }
    });
}

function editWord(wordId) {
    const wordText = document.querySelector(`[onclick="editWord(${wordId})"]`).closest('.w-100').querySelector('strong').textContent.trim();

    Swal.fire({
        title: 'Edit Word',
        html: `
            <input id="editWordInput" class="form-control mb-2" placeholder="Word" value="${wordText}">
        `,
        showCancelButton: true,
        confirmButtonText: 'Save',
        preConfirm: () => {
            return {
                word: document.getElementById('editWordInput').value
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
                    word: result.value.word,
                    update_word: true
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
}

function removeWord(wordId) {
    if (!confirm('Are you sure you want to remove this word?')) return;
    fetch("{{ route('wordCollection.edit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
            location.reload();
        }
    });
}
</script>
@endsection