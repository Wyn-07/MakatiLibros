<?php
// Include your PDO connection
include '../connection.php'; // Adjust the path to your connection file

try {
    // Query to get the latest acc_number
    $stmt = $pdo->query("SELECT acc_number FROM books ORDER BY CAST(acc_number AS UNSIGNED) DESC LIMIT 1");
    $latest_acc_number = $stmt->fetchColumn();

    if ($latest_acc_number) {
        // Extract only the numeric part, ignore any attached string
        preg_match('/\d+/', $latest_acc_number, $matches);
        
        // Check if a numeric part was found and increment it
        $numeric_part = isset($matches[0]) ? intval($matches[0]) + 1 : 1;
        
        // Convert back to a string for the new acc_number
        $new_acc_number = strval($numeric_part);
    }
} catch (PDOException $e) {
    die("Error fetching acc_number: " . $e->getMessage());
}
?>


<div id="addModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Add | Book
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-addbook" style="display: none">
            <div class="container-error-description" id="message_addbook"></div>
            <button type="button" class="button-error-close" onclick="closeErrorAddBookStatus()">&times;</button>
        </div>

        <form action="functions/add_book.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm(['book_image'])">

            <div class="row">
                <div class="modal-content-left">
                    <div class="container-form">

                        <div class="container-input-100">
                            <label for="acc_num">Acc Number</label>
                            <input type="text" id="acc_num" name="acc_num" class="input-text" value="<?php echo $new_acc_number; ?>" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="class_num">Class Number</label>
                            <input type="text" id="class_num" name="class_num" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="title">Book Title</label>
                            <input type="text" name="title" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="author">Author</label>
                            <input type="text" id="author" name="author" class="input-text" autocomplete="off" required>
                            <input type="hidden" id="author_id" name="author_id" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" class="input-text" autocomplete="off" required>
                            <input type="hidden" id="category_id" name="category_id" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="copyright">Copyright</label>
                            <input type="number" id="copyright" name="copyright" class="input-text" autocomplete="off" required>
                        </div>

                    </div>
                </div>


                <div class="modal-content-right">
                    <div class="column">
                        <label for="book_image">Book Image</label>
                        <div class="container-form-book">
                            <div class="form-book">
                                <img src="../book_images/no_image.png" class="image" id="imageBookPreview">
                            </div>
                        </div>
                        <div class="row-center">
                            <div class="container-input-file">
                                <input type="file" class="file" name="book_image" id="book_image" accept="image/*" onchange="previewBookImage(event)">
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
    function previewBookImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageBookPreview = document.getElementById('imageBookPreview');
            imageBookPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function validateForm(filenames) {
        var resultErrorContainer = document.getElementById("container-error-addbook");
        var message = document.getElementById("message_addbook");
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
        return validateForm(['book_image']);
    };
</script>


<script src="js/close-status.js"></script>