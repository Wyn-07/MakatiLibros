document.addEventListener('DOMContentLoaded', function() {
    const borrowButton = document.querySelector('.button-borrow');

    if (borrowButton) {
        borrowButton.addEventListener('click', function() {
            // Get the book ID from the DOM
            const bookId = document.querySelector('.books-contents-id').textContent.trim();

        
            if (bookId && patronId) {
                // Populate the hidden form fields with book and user data
                document.getElementById('bookIdInput').value = bookId;
                document.getElementById('patronIdInput').value = patronId;

                // Submit the form
                document.getElementById('borrowForm').submit();
            }
        });
    }
});