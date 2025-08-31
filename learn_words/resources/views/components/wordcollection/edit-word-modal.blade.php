<!-- Modal para editar palabra -->
<div class="modal fade" id="editWordModal" tabindex="-1" aria-labelledby="editWordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editWordForm" action="javascript:void(0);">
        <div class="modal-header">
          <h5 class="modal-title" id="editWordModalLabel">Edit Word</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editWordInput" class="form-label">Word</label>
            <input type="text" class="form-control" id="editWordInput" name="word">
          </div>
          <div class="mb-3">
            <label for="editTranslationInput" class="form-label">Translation</label>
            <input type="text" class="form-control" id="editTranslationInput" name="translation">
          </div>
          <div class="mb-3">
            <input type="hidden" id="editImageUrlInput" name="image_url">
            <button type="button" class="btn btn-dark" id="editSearchImageBtn">Search Image</button>
          </div>
          <div id="editImageResults" class="row my-2"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>