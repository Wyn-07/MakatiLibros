function openEditModal(id, name, email, username, password,  image) {
    document.getElementById('editModal').classList.add('show');
    document.getElementById('id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('username').value = username;
    document.getElementById('password').value = password;
    document.getElementById('email').value = email;

    var imageSrc = presentedID ? '/request/' + presentedID : '/images/admin/presentedID.png';
    document.getElementById('imagePresentedIdPreview').src = imageSrc;
}


function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');

}

function saveChanges() {
    closeEditModal();
}