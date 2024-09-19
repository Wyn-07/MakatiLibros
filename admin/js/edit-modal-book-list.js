
function openEditModal(date, category, title, author, publisher,  quantity) {
    document.getElementById('editModal').classList.add('show');
    document.getElementById('date').value = date;
    document.getElementById('category').value = category;
    document.getElementById('title').value = title;
    document.getElementById('author').value = author;
    document.getElementById('publisher').value = publisher;
    document.getElementById('quantity').value = quantity;

    var imageSrc = presentedID ? '/request/' + presentedID : '/images/admin/presentedID.png';
    document.getElementById('imagePresentedIdPreview').src = imageSrc;
}


function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');

}

function saveChanges() {
    closeEditModal();
}