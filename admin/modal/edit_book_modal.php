<div id="editModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Book
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-editbook" style="display: none">
            <div class="container-error-description" id="message_editbook"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditBookStatus()">&times;</button>
        </div>

        <form action="functions/update_book.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">

            <div class="row">
                <div class="modal-content-left">
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

                    </div>
                </div>

                <div class="modal-content-right">
                    <div class="column">
                        <label for="book_image">Book Image</label>
                        <div class="container-form-book">
                            <div class="form-book">
                                <img src="../book_images/no_image.png" class="image" id="imageEditBookPreview">
                            </div>
                        </div>
                        <div class="row-center">
                            <div class="container-input-file">
                                <input type="file" class="file" name="edit_book_image" id="edit_book_image" accept="image/*" onchange="previewEditBookImage(event)">
                            </div>
                        </div>
                    </div>


                    <div class="row row-right">
                        <button type="submit" name="submit" class="button-submit">Submit</button>
                    </div>
                </div>
            </div>

        </form>



    </div>
</div>

<script>
function openEditModal(element) {
    const bookId = decodeURIComponent(element.getAttribute("data-book-id"));
    const accNumber = decodeURIComponent(element.getAttribute("data-acc-number"));
    const classNumber = decodeURIComponent(element.getAttribute("data-class-number"));
    const title = decodeURIComponent(element.getAttribute("data-title"));
    const authorName = decodeURIComponent(element.getAttribute("data-author-name"));
    const authorId = decodeURIComponent(element.getAttribute("data-author-id"));
    const categoryName = decodeURIComponent(element.getAttribute("data-category-name"));
    const categoryId = decodeURIComponent(element.getAttribute("data-category-id"));
    const copyright = decodeURIComponent(element.getAttribute("data-copyright"));
    const image = decodeURIComponent(element.getAttribute("data-image"));

    // Show the edit modal
    document.getElementById('editModal').classList.add('show');

    // Populate input fields with decoded values
    document.getElementById('edit_book_id').value = bookId;
    document.getElementById('edit_acc_num').value = accNumber;
    document.getElementById('edit_class_num').value = classNumber;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_author').value = title;
    document.getElementById('edit_author_id').value = authorId;
    document.getElementById('edit_category').value = categoryName;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_copyright').value = copyright;

    // Set the image preview (make sure to handle file paths correctly)
    document.getElementById('imageEditBookPreview').src = '../book_images/' + image;

    // Clear the file input to allow a new selection
    document.getElementById('edit_book_image').value = '';
}


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>


<script>
    function previewEditBookImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageBookPreview = document.getElementById('imageEditBookPreview');
            imageBookPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function validateForm(filenames) {
        var resultErrorContainer = document.getElementById("container-error-editbook");
        var message = document.getElementById("message_editbook");
        message.innerHTML = "";

        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var isValid = true;

        filenames.forEach(function(filename) {
            var fileInput = document.getElementById(filename);
            var filePath = fileInput.value;

            if (!filePath) {
                return;
            }

            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                fileInput.style.border = '2px solid red';
            } else {
                fileInput.style.border = '';
            }
        });

        if (isValid) {
            resultErrorContainer.style.display = "none";
            message.style.display = "none";
        }

        return isValid;
    }



    document.getElementById('form').onsubmit = function() {
        return validateForm(['edit_book_image']);
    };
</script>


<script src="js/close-status.js"></script>