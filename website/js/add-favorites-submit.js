document.addEventListener('DOMContentLoaded', function() {
    const favoriteButton = document.querySelector('.button-bookmark');

    if (favoriteButton) {
        favoriteButton.addEventListener('click', function() {
            // Get the book ID from the DOM
            const addBookId = document.querySelector('.books-contents-id').textContent.trim();

            if (addBookId && addPatronId) {
                // Populate the hidden form fields with book and user data
                document.getElementById('addBookIdInput').value = addBookId;
                document.getElementById('addPatronIdInput').value = addPatronId;

                // Submit the form
                document.getElementById('addFavoriteForm').submit();
            }
        });
    }
});