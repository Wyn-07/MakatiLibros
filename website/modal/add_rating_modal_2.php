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
                <input type="text" name="book_id" id="book_id_field">
                <input type="text" name="patrons_id" id="patrons_id_field">

                <select name="rate" class="input-text" required>
                    <option value="" disabled selected>Select your rating</option>
                    <option value="1">1 star</option>
                    <option value="2">2 stars</option>
                    <option value="3">3 stars</option>
                    <option value="4">4 stars</option>
                    <option value="5">5 stars</option>
                </select>

                <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

                <div class="row row-right">
                    <button type="submit" name="submit" class="button button-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openRateModal(bookId, patronRating, userId) {
        const modal = document.getElementById('rateModal');
        modal.classList.add('show');

        // Set the values of the hidden inputs
        document.getElementById('book_id_field').value = bookId;
        document.getElementById('patrons_id_field').value = userId;

        // Reset the rating select element to default value
        document.querySelector('select[name="rate"]').value = patronRating || ''; // If patronRating is null, set to empty

    }


    function closeRateModal() {
        const modal = document.getElementById('rateModal');
        modal.classList.remove('show');
    }
</script>