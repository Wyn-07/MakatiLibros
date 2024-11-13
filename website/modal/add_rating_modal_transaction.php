<!-- Modal Structure -->
<div id="rateModal" class="modal">
    <div class="modal-content">
        <div class="row row-between">
            <div class="title-26px">Rate</div>
            <span class="modal-close" onclick="closeRateModal()">&times;</span>
        </div>

        <!-- Form Submission -->
        <form action="functions/submit_rating.php" method="POST" id="rateForm">
            <div class="container-form">
                <!-- Hidden Fields -->
                <input type="hidden" name="book_id" id="book_id_field">
                <input type="hidden" name="patrons_id" id="patrons_id_field">

                <!-- Rating Dropdown -->
                <select name="rate" class="input-text" required>
                    <option value="" disabled selected>Select your rating</option>
                    <option value="1">1 star</option>
                    <option value="2">2 stars</option>
                    <option value="3">3 stars</option>
                    <option value="4">4 stars</option>
                    <option value="5">5 stars</option>
                </select>

                <!-- Referer Hidden Field -->
                <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

                <!-- Submit Button -->
                <div class="row row-right">
                    <button type="submit" name="submit" class="button button-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Function to open the modal and fetch user rating if available
    function openRateModal() {
        const modal = document.getElementById('rateModal');
        modal.classList.add('show');

        const bookId = document.querySelector('.books-contents-id').textContent; // Assuming the book ID is stored here
        const userId = <?php echo json_encode($patrons_id); ?>; // PHP variable for user ID

        // Set the values of the hidden inputs (book ID and user ID)
        document.getElementById('book_id_field').value = bookId;
        document.getElementById('patrons_id_field').value = userId;

        // Reset the rating select element to the default value
        document.querySelector('select[name="rate"]').value = '';

        // Fetch existing rating from the server (if needed)
        fetch('functions/get_user_rating.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    book_id: bookId,
                    patrons_id: userId,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rating = data.rating;
                    if (rating) {
                        document.querySelector('select[name="rate"]').value = rating; // Pre-select the user's rating if available
                    }
                } else {
                    console.log(data.message); // Handle any errors if no rating found
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to close the modal
    function closeRateModal() {
        const modal = document.getElementById('rateModal');
        modal.classList.remove('show');
    }
</script>
