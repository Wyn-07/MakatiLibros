<!-- Modal Structure -->
<div id="rateModal" class="modal">
    <div class="modal-content">
        <div class="row row-between">
            <div class="title-26px">Rate</div>
            <span class="modal-close" onclick="closeRateModal()">&times;</span>
        </div>

        <!-- Form Submission -->
        <form action="functions/submit_rating_transaction.php" method="POST" id="rateForm">
            <div class="container-form">
                <!-- Hidden Fields -->
                <input type="hidden" name="bookId" id="bookId">
                <input type="hidden" name="patronID" id="patronID">

                <!-- Rating Dropdown -->
                <select id="rate" name="rate" class="input-text" required>
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
    function openRateModal(bookID, patronID, rating) {
    document.getElementById('rateModal').classList.add('show');

    document.getElementById('bookId').value = bookID;
    document.getElementById('patronID').value = patronID;

    // Set the selected option in the dropdown
    const rateDropdown = document.getElementById('rate');
    rateDropdown.value = rating || ''; // Handle empty ratings gracefully

    console.log({
        bookId: bookID,
        patronID: patronID,
        rating: rating,
    });
}



    function closeRateModal() {
        document.getElementById('rateModal').classList.remove('show');
    }

    function saveChanges() {
        closeRateModal();
    }
</script>