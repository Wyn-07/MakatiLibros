<div id="deleteModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Delete | Category
            </div>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <div class="container-form">

                <div style="text-align: center; margin-bottom: 10px;">
                    Are you sure you want to delete?
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
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');

    }

    function saveChanges() {
        openDeleteModal();
    }
</script>