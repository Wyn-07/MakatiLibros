<?php
session_start();

include '../../connection.php'; 

if (isset($_POST['submit'])) {
    // Sanitize and validate input fields
    $accNumber = filter_var($_POST['acc_num'], FILTER_SANITIZE_STRING);
    $classNumber = filter_var($_POST['class_num'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $authorId = filter_var($_POST['author_id'], FILTER_VALIDATE_INT);
    $categoryId = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $copyright = filter_var($_POST['copyright'], FILTER_VALIDATE_INT);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_STRING); 

    // Validate required fields
    if (!empty($accNumber) && !empty($classNumber) && !empty($title) && !empty($authorId) && !empty($categoryId) && !empty($copyright)) {
        try {
            // Prepare the SQL statement to insert the missing book
            $stmt = $pdo->prepare("INSERT INTO missing (acc_number, class_number, title, author_id, category_id, copyright, image) 
                                   VALUES (:acc_number, :class_number, :title, :author_id, :category_id, :copyright, :image)");

            // Bind parameters to the SQL query
            $stmt->bindParam(':acc_number', $accNumber);
            $stmt->bindParam(':class_number', $classNumber);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author_id', $authorId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':copyright', $copyright);
            $stmt->bindParam(':image', $image); 

            // Execute the statement and check if successful
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Missing book added successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add the missing book';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            // Handle SQL errors
            $_SESSION['error_message'] = 'Failed to add missing book. Error: ' . $e->getMessage();
        }

        // Redirect back to the form or a success page
        header('Location: ../missing.php'); 
        exit();
    } else {
        // Set an error message if any field is empty or invalid
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: ../missing.php'); 
        exit();
    }
}
?>
