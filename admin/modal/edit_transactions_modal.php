<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Status
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_transactions.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editBorrowId" name="editBorrowId">
            <input type="hidden" id="editOldStatus" name="editOldStatus">

            <div class="container-form">

                <div>
                    <select class="input-text" id="editStatus" name="editStatus" required>
                        <option value="Pending">Pending</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Borrowed">Borrowed</option>
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
        document.getElementById('editOldStatus').value = status;


        // Get the select element
        const statusSelect = document.getElementById('editStatus');

        // Show all options initially
        statusSelect.querySelector('option[value="Pending"]').style.display = '';
        statusSelect.querySelector('option[value="Accepted"]').style.display = '';
        statusSelect.querySelector('option[value="Borrowed"]').style.display = '';
        statusSelect.querySelector('option[value="Returned"]').style.display = '';


        // Hide the "Returned" option if status is "Pending"
        if (status === 'Pending') {
            statusSelect.querySelector('option[value="Borrowed"]').style.display = 'none';
            statusSelect.querySelector('option[value="Returned"]').style.display = 'none';
        }


        // Hide the "Borrowed" option if status is "Accepted"
        if (status === 'Accepted') {
            statusSelect.querySelector('option[value="Returned"]').style.display = 'none';
        }


        // Hide the "Pending" option if status is "Borrowed"
        if (status === 'Borrowed') {
            statusSelect.querySelector('option[value="Pending"]').style.display = 'none';
        }


        // Hide the "Pending" and "Acccepted" option if status is "Returned"
        if (status === 'Returned') {
            statusSelect.querySelector('option[value="Pending"]').style.display = 'none';
            statusSelect.querySelector('option[value="Accepted"]').style.display = 'none';

        }


    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>