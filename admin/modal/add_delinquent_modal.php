<div id="deleteModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add | Delinquent
            </div>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>

        <form action="functions/add_delinquent.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <div class="container-form">

                <input type="text" id="borrowID" name="borrowID" class="input-text" autocomplete="off" required>

                <div style="text-align: center; margin-bottom: 10px;">
                    Are you sure you want to mark this patron as delinquent? 
                </div>

                <div class="row row-center">
                    <button name="cancel" class="button-cancel">No</button>
                    <button type="submit" name="submit" class="button-submit">Yes</button>
                </div>
                
            </div>
        </form>
    </div>
</div>



<script>
function openDeleteModal(borrowID) {
    const borrowIDInput = document.getElementById('borrowID');
    if (borrowIDInput) {
        borrowIDInput.value = borrowID;
        document.getElementById('deleteModal').classList.add('show');
    } else {
        console.error('Element with ID "borrowID" not found!');
    }
}



    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');

    }

    function saveChanges() {
        openDeleteModal();
    }
</script>