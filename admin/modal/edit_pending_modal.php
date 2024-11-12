<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Pending Book
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_pending.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editBorrowId" name="editBorrowId">
            <div class="container-form">

                <div>
                    <label for="status">Status</label>
                    <select class="input-text" id="status" name="status" required>
                        <option value="Pending" selected>Pending</option>
                        <option value="Borrowing">Borrowing</option>
                    </select>
                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>
        </form>

    </div>
</div>




<script>
    function openEditModal(borrowId) {
        document.getElementById('editModal').classList.add('show');
        document.getElementById('editBorrowId').value = borrowId;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>