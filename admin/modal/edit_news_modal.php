<div id="editModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Edit | News
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-news" style="display: none">
            <div class="container-error-description" id="message-news"></div>
            <button type="button" class="button-error-close" onclick="closeErrorNewsStatus()">&times;</button>
        </div>

        <form action="functions/update_news.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateNewsForm(['edit_image_news'])">
            <div class="container-form-official">

                <input type="text" name="editNewsId" id="editNewsId" class="input-text" autocomplete="off" required>


                <div class="container-officials-image-modal">
                    <img
                        alt="News Image"
                        id="imageEditNewsPreview"
                        class="image"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                <input type="file" class="file" name="edit_image_news" id="edit_image_news" accept="image/*" onchange="previewEditNewsImage(event)">

                <div class="container-input-100">
                    <label for="title">Title</label>
                    <input type="text" name="editTitle" id="editTitle" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="date">Date</label>
                    <input type="date" name="editDate" id="editDate" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="description">Description</label>
                    <textarea name="editDescription" class="textarea-news" id="editDescription"></textarea>
                </div>

                <div class="row row-between">
                    <button type="button" class="button-delete-big" onclick="openDeleteModal(document.getElementById('editNewsId').value)">Delete</button>
                    <button type="submit" name="submit" class="button-submit">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'modal/delete_news_modal.php'; ?>

<script>
    function openEditModal(newsId, title, date, description, image) {
        document.getElementById('editModal').classList.add('show');
        document.getElementById('editNewsId').value = newsId;
        document.getElementById('editTitle').value = title;
        document.getElementById('editDate').value = date;
        document.getElementById('editDescription').value = description;

        document.getElementById('imageEditNewsPreview').src = '../news_images/' + image;;

        // Clear the file input to allow a new selection
        document.getElementById('edit_image_news').value = '';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function saveChanges() {
        closeEditModal();
    }

    function previewEditNewsImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageNewsPreview = document.getElementById('imageEditNewsPreview');
            imageNewsPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>





<script>
    function validateNewsForm(fileInputs) {
        var resultErrorContainer = document.getElementById("container-error-news");
        var message = document.getElementById("message-news");
        message.innerHTML = "";

        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var isValid = true;

        // Validate file inputs
        fileInputs.forEach(function(filename) {
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
                fileInput.style.border = '2px solid red'; // Highlight the invalid input
            } else {
                fileInput.style.border = ''; // Reset the border if valid
            }
        });


        // Hide error messages if everything is valid
        if (isValid) {
            resultErrorContainer.style.display = "none"; // Hide error container if all inputs are valid
            message.style.display = "none"; // Hide message
        }

        return isValid; // Return true if all inputs are valid
    }
</script>