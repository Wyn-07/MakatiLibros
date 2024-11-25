<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit Application
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-editpatron" style="display: none">
            <div class="container-error-description" id="message-editpatron"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditPatronsStatus()">&times;</button>
        </div>

        <form action="functions/update_application.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" id="edit_patron_id" name="edit_patron_id">
            <input type="hidden" id="editOldStatus" name="editOldStatus">
            <input type="hidden" id="edit_guarantor_id" name="edit_guarantor_id">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-input-100">
                        <div class="row">
                            <label for="editStatus">Status:</label>
                        </div>
                        <select class="input-text" id="editStatus" name="editStatus" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="container-input-100" id="reasonContainer" style="display: none">
                        <div class="row">
                            <label for="editReason">Reason:</label>
                        </div>
                        <textarea type="text" id="editReason" name="editReason" class="textarea-reason"></textarea>
                    </div>

                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Update</button>
                </div>

            </div>


        </form>


    </div>
</div>


<script>
    function openEditModal(element) {
        // Retrieve data attributes from the clicked element
        const patronId = decodeURIComponent(element.getAttribute('data-edit-patrons-id'));
        const guarantorId = decodeURIComponent(element.getAttribute('data-edit-guarantor-id'));
        const status = decodeURIComponent(element.getAttribute('data-edit-status'));

        // Show the modal
        document.getElementById('editModal').classList.add('show');

        // Populate the modal fields with the decoded values
        document.getElementById('edit_patron_id').value = patronId;
        document.getElementById('edit_guarantor_id').value = guarantorId;
        document.getElementById('editStatus').value = status;
        document.getElementById('editOldStatus').value = status;
        document.getElementById('editReason').value = reason;

    }



    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
    
</script>


<script>
    function previewEditPatronsImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageBookPreview = document.getElementById('imageEditPatronsPreview');
            imageBookPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script src="js/input-validation-editpatrons.js"></script>
<script src="js/close-status.js"></script>



<script>
    // Get the status select element and reason container
    const editStatus = document.getElementById('editStatus');
    const reasonContainer = document.getElementById('reasonContainer');

    // Event listener for status change
    editStatus.addEventListener('change', function() {
        if (editStatus.value === 'Rejected') {
            // Show the reason container if "Rejected" is selected
            reasonContainer.style.display = 'block';
        } else {
            // Hide the reason container for other options
            reasonContainer.style.display = 'none';
        }
    });
</script>