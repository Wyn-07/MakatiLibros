document.addEventListener('DOMContentLoaded', function() {
    const favoriteButton = document.querySelector('.button-bookmark-red');

    if (favoriteButton) {
        favoriteButton.addEventListener('click', function() {
            // Get the book ID from the DOM
            const removeBookId = document.querySelector('.books-contents-id').textContent.trim();

            // Get the user ID from PHP (passed into the script)

            if (removeBookId && removePatronId) {
                // Populate the hidden form fields with book and user data
                document.getElementById('removeBookIdInput').value = removeBookId;
                document.getElementById('removePatronIdInput').value = removePatronId;

                // Submit the form
                document.getElementById('removeFavoriteForm').submit();
            }
        });
    }
});