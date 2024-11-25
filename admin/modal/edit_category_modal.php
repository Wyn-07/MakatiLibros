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
            <input type="hidden" id="oldName" name="oldName">
            <input type="hidden" id="old_edit_categ_description" name="old_edit_categ_description">

           
            <div class="container-form">

                <div class="container-input-100">
                    <label for="editName">Category Name</label>
                    <input type="text" id="editName" name="name" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="edit_categ_description">Category Description</label>
                    <textarea type="text"  id="edit_categ_description" name="edit_categ_description" class="textarea-category" autocomplete="off" required></textarea>
                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>
        </form>

    </div>
</div>




<script>
    function openEditModal(element) {
        const categoryId = decodeURIComponent(element.getAttribute("data-category-id"));
        const categoryName = decodeURIComponent(element.getAttribute("data-category-name"));
        const categoryDescription = decodeURIComponent(element.getAttribute("data-category-description"));


        document.getElementById('editModal').classList.add('show');
        document.getElementById('editcategoryId').value = categoryId;
        document.getElementById('editName').value = categoryName;
        document.getElementById('edit_categ_description').value = categoryDescription;


        document.getElementById('oldName').value = categoryName;
        document.getElementById('old_edit_categ_description').value = categoryDescription;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>