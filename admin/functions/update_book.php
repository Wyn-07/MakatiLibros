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

    // Validate required fields
    if (!empty($bookId) && !empty($accNumber) && !empty($classNumber) && !empty($title) && !empty($authorId) && !empty($categoryId) && !empty($copyright)) {
        try {
            // Prepare the SQL statement to update the book
            $stmt = $pdo->prepare("UPDATE books SET acc_number = :acc_number, class_number = :class_number, 
                                   title = :title, author_id = :author_id, category_id = :category_id, copyright = :copyright 
                                   WHERE book_id = :book_id");

            // Bind parameters to the SQL query
            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':acc_number', $accNumber);
            $stmt->bindParam(':class_number', $classNumber);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author_id', $authorId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':copyright', $copyright);

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
