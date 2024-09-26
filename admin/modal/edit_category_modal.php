<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Category
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_category.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editcategoryId" name="category_id">
            <div class="container-form">

                <div class="container-input-100">
                    <label for="editName">Category Name</label>
                    <input type="text" id="editName" name="name" class="input-text" autocomplete="off" required>
                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>
        </form>

    </div>
</div>




<script>
    function openEditModal(categoryId, categoryName) {
        document.getElementById('editModal').classList.add('show');
        document.getElementById('editcategoryId').value = categoryId;
        document.getElementById('editName').value = categoryName;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>