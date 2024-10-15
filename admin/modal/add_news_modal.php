<div id="addModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Add | News
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-addNews" style="display: none">
            <div class="container-error-description" id="message-addNews"></div>
            <button type="button" class="button-error-close" onclick="closeErrorAddNewsStatus()">&times;</button>
        </div>

        <form action="functions/add_news.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateAddNewsForm(['image_news'])">
            <div class="container-form-official">

                <div class="container-officials-image-modal">
                    <img src="../news_images/no_image.png" alt="Official Image" id="imageNewsPreview" class="image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                <input type="file" class="file" name="image_news" id="image_news" accept="image/*" onchange="previewNewsImage(event)">

                <div class="container-input-100">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="input-text" autocomplete="off" required required id="newsDate">
                </div>

                <div class="container-input-100">
                    <label for="date">Description</label>
                    <textarea name="description" class="textarea-news" id="description"></textarea>
                </div>



                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('show');

    }

    function saveChanges() {
        closeAddModal();
    }
</script>


<script>
    function previewNewsImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageNewsPreview = document.getElementById('imageNewsPreview');
            imageNewsPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>



<script>
    // Get the current date in the Philippines timezone (Asia/Manila)
    const today = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Manila' });
    
    // Set today's date as the default value for the date input
    document.getElementById('newsDate').value = today;
</script>



<script>
    function validateAddNewsForm(fileInputs) {
        var resultErrorContainer = document.getElementById("container-error-addNews");
        var message = document.getElementById("message-addNews");
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