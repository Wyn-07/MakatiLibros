<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add | Official
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-official" style="display: none">
            <div class="container-error-description" id="message-official"></div>
            <button type="button" class="button-error-close" onclick="closeErrorOfficialStatus()">&times;</button>
        </div>

        <form action="functions/add_official.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateOfficialForm(['image_official'])">
            <div class="container-form-official">

                <div class="container-officials-image-modal">
                    <img src="../official_images/default-image.jfif" alt="Official Image" id="imageOfficialPreview" class="image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                <input type="file" class="file" name="image_official" id="image_official" accept="image/*" onchange="previewOfficialImage(event)">


                <div class="container-input-100">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="input-text" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="input-text" autocomplete="off" required>
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
    function previewOfficialImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageOfficialPreview = document.getElementById('imageOfficialPreview');
            imageOfficialPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script>
  
function validateOfficialForm(fileInputs) {
    var resultErrorContainer = document.getElementById("container-error-official");
    var message = document.getElementById("message-official");
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