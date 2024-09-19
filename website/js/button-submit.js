document.addEventListener('DOMContentLoaded', function() {
    const borrowButton = document.querySelector('.button-borrow');

    if (borrowButton) {
        borrowButton.addEventListener('click', function() {
            // Get the book ID from the DOM
            const bookId = document.querySelector('.books-contents-id').textContent.trim();

            // Get the user ID from PHP (passed into the script)
            const userId = <?php echo json_encode($user_id); ?>;

            if (bookId && userId) {
                // Populate the hidden form fields with book and user data
                document.getElementById('bookIdInput').value = bookId;
                document.getElementById('userIdInput').value = userId;

                // Submit the form
                document.getElementById('borrowForm').submit();
            }
        });
    }
});






