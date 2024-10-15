<div id="viewModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                View | Missing Book
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>

        <form action="functions/update_missing.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">

            <div class="row">

                <div class="modal-content-left">
                    <div class="container-form">
                        <input type="hidden" id="view_missing_id" name="view_missing_id" class="input-text" autocomplete="off" required>

                        <div class="container-input-100">
                            <label for="view_acc_num">Acc Number</label>
                            <input type="text" id="view_acc_num" name="view_acc_num" class="input-text" autocomplete="off" disabled>
                        </div>

                        <div class="container-input-100">
                            <label for="view_class_num">Class Number</label>
                            <input type="text" id="view_class_num" name="view_class_num" class="input-text" autocomplete="off" disabled>
                        </div>

                        <div class="container-input-100">
                            <label for="view_title">Book Title</label>
                            <input type="text" id="view_title" name="view_title" class="input-text" autocomplete="off" disabled>
                        </div>


                        <div class="container-input-100">
                            <label for="view_author">Author</label>
                            <input type="text" id="view_author" name="view_author" class="input-text" autocomplete="off" disabled>
                            <input type="hidden" id="view_author_id" name="view_author_id" class="input-text" autocomplete="off" required>
                        </div>


                        <div class="container-input-100">
                            <label for="view_category">Category</label>
                            <input type="text" id="view_category" name="view_category" class="input-text" autocomplete="off" disabled>
                            <input type="hidden" id="view_category_id" name="view_category_id" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="view_copyright">Copyright</label>
                            <input type="number" id="view_copyright" name="view_copyright" min="1000" class="input-text" autocomplete="off" disabled>
                        </div>

                    </div>
                </div>

                <div class="modal-content-right">
                    <div class="column">
                        <label for="book_image">Book Image</label>
                        <div class="container-form-book">
                            <div class="form-book">
                                <img src="../book_images/book_sample.png" class="image" id="imageViewBookPreview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </form>



    </div>
</div>

<script>
    function openViewModal(bookId, accNum, classNum, title, author, authorId, category, categoryId, copyright, image) {
        document.getElementById('viewModal').classList.add('show');

        document.getElementById('view_missing_id').value = bookId;
        document.getElementById('view_acc_num').value = accNum;
        document.getElementById('view_class_num').value = classNum;
        document.getElementById('view_title').value = title;
        document.getElementById('view_author').value = author;
        document.getElementById('view_author_id').value = authorId;
        document.getElementById('view_category').value = category;
        document.getElementById('view_category_id').value = categoryId;
        document.getElementById('view_copyright').value = copyright;

        document.getElementById('imageViewBookPreview').src = '../book_images/' + image;;

    }


    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');

    }

    function saveChanges() {
        closeviewModal();
    }
</script>


