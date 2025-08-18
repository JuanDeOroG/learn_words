<!-- Modal -->
<div class="modal fade" id="chooseCollectionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="chooseCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Choose one or more Word Collections</h1>
                <!-- se ha eliminado el botón de cerrar (equis) -->
            </div>
            <div class="modal-body" style="max-height: 350px; overflow-y: auto;">
                <input type="text" class="form-control mb-2" placeholder="Search Collections..." id="search-collection"
                    onkeyup="filterCollections()">
                <div id="collection-error" class="text-danger mb-2" style="display:none;"></div>
                <div id="collections-list" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                    @foreach ($collections as $collection)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="collections[]"
                                value="{{ $collection->id }}" id="col_{{ $collection->id }}">
                            <label class="form-check-label" for="col_{{ $collection->id }}">
                                {{ $collection->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="validateCollections()">Choose</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // validate that at least one checkbox is selected before closing the modal
    function validateCollections() {
        const checks = document.querySelectorAll('#collections-list input[type="checkbox"]');
        let checked = Array.from(checks).some(c => c.checked);
        const errorDiv = document.getElementById('collection-error');
        if (!checked) {
            errorDiv.textContent = 'Please select at least one collection.';
            errorDiv.style.display = 'block';
            return;
        }
        errorDiv.style.display = 'none';
        // close the modal if at least one is selected
        let modal = bootstrap.Modal.getInstance(document.getElementById('chooseCollectionModal'));
        modal.hide();
    }
</script>
@endsection