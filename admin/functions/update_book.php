<?php
session_start();

include '../../connection.php'; // Include the database connection file

if (isset($_POST['submit'])) {
    // Sanitize and validate input fields
    $bookId = filter_var($_POST['edit_book_id'], FILTER_VALIDATE_INT);
    $accNumber = filter_var($_POST['edit_acc_num'], FILTER_SANITIZE_STRING);
    $classNumber = filter_var($_POST['edit_class_num'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['edit_title'], FILTER_SANITIZE_STRING);
    $authorId = filter_var($_POST['edit_author_id'], FILTER_VALIDATE_INT);
    $categoryId = filter_var($_POST['edit_category_id'], FILTER_VALIDATE_INT);
    $copyright = filter_var($_POST['edit_copyright'], FILTER_VALIDATE_INT);

    // Initialize image variable
    $imageName = null;

    // Process the image
    if (isset($_FILES['edit_book_image']) && $_FILES['edit_book_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['edit_book_image'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Trim accNumber and title to remove leading/trailing spaces
        $accNumberTrim = trim($accNumber);
        $titleTrim = trim($title);

        // Function to sanitize the string for the image name
        function sanitizeFileName($string)
        {
            // Remove special characters, allowing only alphanumeric characters
            return preg_replace('/[^A-Za-z0-9]/', '', $string); // Allow only alphanumeric characters
        }

        // Sanitize accNumber and title
        $accNumberSanitize = sanitizeFileName($accNumberTrim);
        $titleSanitize = sanitizeFileName($titleTrim);

        // Define the image name format: "accNumber_title_date_time"
        $imageName = $accNumberSanitize . '_' . $titleSanitize . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../book_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../book-list.php');
            exit();
        }
    }

    // Validate required fields
    if (!empty($bookId) && !empty($accNumber) && !empty($classNumber) && !empty($title) && !empty($authorId) && !empty($categoryId) && !empty($copyright)) {
        try {
            // Prepare the SQL statement to update the book
            $sql = "UPDATE books SET 
                        acc_number = :acc_number, 
                        class_number = :class_number, 
                        title = :title, 
                        author_id = :author_id, 
                        category_id = :category_id, 
                        copyright = :copyright" . 
                        (!empty($imageName) ? ", image = :image" : "") . 
                    " WHERE book_id = :book_id";

            // Prepare the statement
            $stmt = $pdo->prepare($sql);

            // Bind parameters to the SQL query
            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':acc_number', $accNumber);
            $stmt->bindParam(':class_number', $classNumber);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author_id', $authorId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':copyright', $copyright);

            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the statement and check if successful
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Book updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update the book';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            // Handle SQL errors
            $_SESSION['error_message'] = 'Failed to update book. Error: ' . $e->getMessage();
        }

        // Redirect back to the form or a success page
        header('Location: ../book-list.php');
        exit();
    } else {
        // Set an error message if any field is empty or invalid
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: ../book-list.php');
        exit();
    }
}
?>
