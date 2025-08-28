{{-- filepath: /home/ubuntu/learn_words/learn_words/resources/views/components/wordcollection/create.blade.php --}}
<div class="modal fade" id="createCollectionModal" tabindex="-1" aria-labelledby="createCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createCollectionForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCollectionModalLabel">Create Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="collectionName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="collectionName" name="name" required maxlength="255" placeholder="Enter collection name">
                        <div class="invalid-feedback">Name is required and must be unique.</div>
                    </div>
                    <div class="mb-3">
                        <label for="collectionDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="collectionDescription" name="description" rows="2" placeholder="Enter a brief description"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-column align-items-stretch gap-2">
                    <button type="submit" class="btn btn-primary w-100">Create</button>
                    <button type="button" id="addWordsBtn" class="btn btn-success w-100 d-none">Add Words</button>
                    <button type="button" id="editCollectionBtn" class="btn btn-secondary w-100 d-none">Edit Collection</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createCollectionForm');
    const addWordsBtn = document.getElementById('addWordsBtn');
    const editCollectionBtn = document.getElementById('editCollectionBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        form.classList.remove('was-validated');
        form.name.classList.remove('is-invalid');
        addWordsBtn.classList.add('d-none');
        editCollectionBtn.classList.add('d-none');

        const data = {
            name: form.name.value.trim(),
            description: form.description.value.trim()
        };

        fetch("{{ route('wordCollection.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest" // <-- Añade este header
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                addWordsBtn.classList.remove('d-none');
                editCollectionBtn.classList.remove('d-none');
                addWordsBtn.onclick = () => window.location.href = `{{route('wordCollection.add')}}?collection_id=${result.collection.id}`;
                editCollectionBtn.onclick = () => window.location.href = `{{route('wordCollection.edit')}}?collection_id=${result.collection.id}`;
                Swal.fire({
                    text: "Collection created successfully.",
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    position: 'center',
                    showConfirmButton: false,
                    width: 300
                });
            } else if (result.errors) {
                form.classList.add('was-validated');
                if (result.errors.name) {
                    form.name.classList.add('is-invalid');
                    form.querySelector('.invalid-feedback').textContent = result.errors.name[0];
                }
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Server error',
                text: error,
                icon: 'error',
                timer: 4000,
                timerProgressBar: true,
                position: 'center',
                showConfirmButton: false,
                width: 300
            });
        });
    });
});
</script>
@endsection
