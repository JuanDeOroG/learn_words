


function selectImage(url) {
    document.getElementById('imageUrlInput').value = url;
    document.querySelectorAll('.select-image').forEach(img => img.classList.remove('border-success'));
    document.querySelectorAll(`img[src="${url}"]`).forEach(img => img.classList.add('border-success'));
}

function editWord(wordId, btn) {
    const row = btn.closest('.list-group-item');
    const wordText = row.querySelector('strong').textContent.trim();
    const translationText = row.querySelector('small') ? row.querySelector('small').textContent.trim() : '';
    const imageUrl = row.querySelector('img') ? row.querySelector('img').getAttribute('src') : '';

    document.getElementById('editWordInput').value = wordText;
    document.getElementById('editTranslationInput').value = translationText;
    document.getElementById('editImageUrlInput').value = imageUrl;
    document.getElementById('editImageResults').innerHTML = imageUrl ? `<img src="${imageUrl}" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;">` : '';

    // Guarda el id para el submit
    document.getElementById('editWordForm').setAttribute('data-word-id', wordId);

    // Abre el modal
    var modal = new bootstrap.Modal(document.getElementById('editWordModal'));
    modal.show();
}

document.getElementById('editSearchImageBtn').onclick = function() {
    const query = document.getElementById('editWordInput').value;
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
        data.images.forEach(url => {
            html += `
                <div style="width:120px; height:120px; display:flex; align-items:center; justify-content:center; border-radius:8px; overflow:hidden; background:#f8f9fa;">
                    <img src="${url}" class="img-thumbnail select-image" style="width:120px; height:120px; object-fit:cover; cursor:pointer;" onclick="selectEditImage('${url}')">
                </div>
            `;
        });
        html += '</div>';
        document.getElementById('editImageResults').innerHTML = html;
    });
};

function selectEditImage(url) {
    document.getElementById('editImageUrlInput').value = url;
    document.querySelectorAll('#editImageResults .select-image').forEach(img => img.classList.remove('border-success'));
    document.querySelectorAll(`#editImageResults img[src="${url}"]`).forEach(img => img.classList.add('border-success'));
}

document.getElementById('editWordForm').onsubmit = function(e) {
    e.preventDefault();
    const wordId = this.getAttribute('data-word-id');
    const data = {
        word_id: wordId,
        word: document.getElementById('editWordInput').value,
        translation: document.getElementById('editTranslationInput').value,
        image_url: document.getElementById('editImageUrlInput').value,
        update_word: true
    };
    fetch(window.wordCollectionEditUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
};
