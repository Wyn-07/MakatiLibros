<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Status
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_transactions.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="text" id="editBorrowId" name="editBorrowId">
            <div class="container-form">

                <div>
                    <select class="input-text" id="editStatus" name="editStatus" required>
                        <option value="Pending">Pending</option>
                        <option value="Borrowing">Borrowing</option>
                        <option value="Returned">Returned</option>
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
    function openEditModal(borrowId, status) {

        document.getElementById('editModal').classList.add('show');
        document.getElementById('editBorrowId').value = borrowId;
        document.getElementById('editStatus').value = status;


        // Get the select element
        const statusSelect = document.getElementById('editStatus');

        // Show all options initially
        statusSelect.querySelector('option[value="Pending"]').style.display = '';
        statusSelect.querySelector('option[value="Returned"]').style.display = '';

        // Hide the "Pending" option if status is "Returned"
        if (status === 'Returned') {
            statusSelect.querySelector('option[value="Pending"]').style.display = 'none';
        }

        // Hide the "Returned" option if status is "Pending"
        if (status === 'Pending') {
            statusSelect.querySelector('option[value="Returned"]').style.display = 'none';
        }
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>