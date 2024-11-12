<div id="editModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Contact
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-contact" style="display: none">
            <div class="container-error-description" id="message-contact"></div>
            <button type="button" class="button-error-close" onclick="closeErrorContactStatus()">&times;</button>
        </div>

        <form action="functions/update_contact.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateContactForm(['edit_image_contact'])">
            <div class="container-form-official">

                <input type="hidden" name="editContactId" id="editContactId" class="input-text" autocomplete="off" required>


                <div class="container-officials-image-modal">
                    <img
                        alt="Contact Image"
                        id="imageEditContactPreview"
                        class="image"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                <input type="file" class="file" name="edit_image_contact" id="edit_image_contact" accept="image/*" onchange="previewEditContactImage(event)">

                <div class="container-input-100">
                    <label for="title">Title</label>
                    <input type="text" name="editTitle" id="editTitle" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="editContactNum">Contact</label>
                    <input type="text" name="editContactNum" id="editContactNum" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="description">Description</label>
                    <textarea name="editDescription" class="textarea-contact" id="editDescription"></textarea>
                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(contactId, title, description, contactNum, image) {
        console.log(contactId, title, description, contactNum, image);
        document.getElementById('editModal').classList.add('show');
        document.getElementById('editContactId').value = contactId;
        document.getElementById('editTitle').value = title;
        document.getElementById('editContactNum').value = contactNum;
        document.getElementById('editDescription').value = description;

        document.getElementById('imageEditContactPreview').src = '../contact_images/' + image;;

        // Clear the file input to allow a new selection
        document.getElementById('edit_image_contact').value = '';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function saveChanges() {
        closeEditModal();
    }

    function previewEditContactImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageContactPreview = document.getElementById('imageEditContactPreview');
            imageContactPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>




<script>
    function validateContactForm(fileInputs) {
        var resultErrorContainer = document.getElementById("container-error-contact");
        var message = document.getElementById("message-contact");
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
                fileInput.style.border = '2px solid red'; 
            } else {
                fileInput.style.border = ''; 
            }
        });


        // Hide error messages if everything is valid
        if (isValid) {
            resultErrorContainer.style.display = "none"; 
            message.style.display = "none"; 
        }

        return isValid; 
    }
</script>


<script>
    const textAreas = [
        document.getElementById("editDescription")
    ];

    textAreas.forEach(textBox => {
        textBox.addEventListener("keydown", function(event) {
            if (event.key === "Enter" || event.keyCode === 13) {
                event.preventDefault();
                const cursorPosition = textBox.selectionStart;
                const text = textBox.value;
                const newText =
                    text.slice(0, cursorPosition) + "<br>\n" + text.slice(cursorPosition);
                textBox.value = newText;
            }
        });
    });
</script>