<div id="deleteModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Delete | Official
            </div>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>

        <form action="functions/delete_official.php" method="POST">
            <div class="container-form">

                <input type="hidden" name="official_id" id="official_id">
                <input type="hidden" name="oldImageName" value="<?php echo htmlspecialchars($official['image']); ?>">
                <input type="hidden" name="oldName" value="<?php echo htmlspecialchars($official['name']); ?>">
                <input type="hidden" name="oldTitle" value="<?php echo htmlspecialchars($official['title']); ?>">


                <div style="text-align: center; margin-bottom: 10px;">
                    Are you sure you want to delete?
                </div>


                <div class="row row-center">
                    <button type="button" name="cancel" class="button-cancel" onclick="closeDeleteModal()">No</button>
                    <button type="submit" name="submit" class="button-submit">Yes</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    function openDeleteModal(officialId) {
        document.getElementById('deleteModal').classList.add('show');

        document.getElementById('official_id').value = officialId;

    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');

    }

    function saveChanges() {
        openDeleteModal();
    }
</script>