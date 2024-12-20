<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Author
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_author.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editAuthorId" name="author_id">
            <input type="hidden" id="oldName" name="oldName">

            <div class="container-form">

                <div class="container-input-100">
                    <label for="editName">Author Name</label>
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
    function openEditModal(element) {
    const authorId = element.getAttribute("data-author-id");
    const encodedAuthorName = element.getAttribute("data-author-name");
    
    // Decode the URI encoded author name
    const authorName = decodeURIComponent(encodedAuthorName);

    // Open modal and populate fields
    document.getElementById('editModal').classList.add('show');
    document.getElementById('editAuthorId').value = authorId;
    document.getElementById('editName').value = authorName;

    document.getElementById('oldName').value = authorName;

}



    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>