<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Book
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_book.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <div class="container-form">
                <input type="hidden" id="edit_book_id" name="edit_book_id" class="input-text" autocomplete="off" required>

                <div class="container-input-100">
                    <label for="edit_acc_num">Acc Number</label>
                    <input type="text" id="edit_acc_num" name="edit_acc_num" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="edit_class_num">Class Number</label>
                    <input type="text" id="edit_class_num" name="edit_class_num" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="edit_title">Book Title</label>
                    <input type="text" id="edit_title" name="edit_title" class="input-text" autocomplete="off" required>
                </div>


                <div class="container-input-100">
                    <label for="edit_author">Author</label>
                    <input type="text" id="edit_author" name="edit_author" class="input-text" autocomplete="off" required>
                    <input type="hidden" id="edit_author_id" name="edit_author_id" class="input-text" autocomplete="off" required>
                </div>


                <div class="container-input-100">
                    <label for="edit_category">Category</label>
                    <input type="text" id="edit_category" name="edit_category" class="input-text" autocomplete="off" required>
                    <input type="hidden" id="edit_category_id" name="edit_category_id" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="edit_copyright">Copyright</label>
                    <input type="number" id="edit_copyright" name="edit_copyright" min="1000" class="input-text" autocomplete="off" required>
                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>
        </form>



    </div>
</div>

<script>
    function openEditModal(bookId, accNum, classNum, title, author, authorId, category, categoryId, copyright) {
        document.getElementById('editModal').classList.add('show');

        document.getElementById('edit_book_id').value = bookId;
        document.getElementById('edit_acc_num').value = accNum;
        document.getElementById('edit_class_num').value = classNum;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_author').value = author;
        document.getElementById('edit_author_id').value = authorId;
        document.getElementById('edit_category').value = category;
        document.getElementById('edit_category_id').value = categoryId;
        document.getElementById('edit_copyright').value = copyright;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>