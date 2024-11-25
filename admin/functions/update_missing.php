<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {

    $missingId = filter_var($_POST['edit_missing_id'], FILTER_VALIDATE_INT); 
    $accNumber = filter_var($_POST['edit_acc_num'], FILTER_SANITIZE_STRING);
    $classNumber = filter_var($_POST['edit_class_num'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['edit_title'], FILTER_SANITIZE_STRING);
    $authorId = filter_var($_POST['edit_author_id'], FILTER_VALIDATE_INT);
    $categoryId = filter_var($_POST['edit_category_id'], FILTER_VALIDATE_INT);
    $copyright = filter_var($_POST['edit_copyright'], FILTER_VALIDATE_INT);
    $image = filter_var($_POST['edit_image'], FILTER_SANITIZE_STRING);  

    if (!empty($missingId) && !empty($accNumber) && !empty($classNumber) && !empty($title) && !empty($authorId) && !empty($categoryId) && !empty($copyright)) {
        try {
            // Prepare the SQL statement to update the missing book
            $stmt = $pdo->prepare("UPDATE missing SET acc_number = :acc_number, class_number = :class_number, 
                                   title = :title, author_id = :author_id, category_id = :category_id, copyright = :copyright, image = :image 
                                   WHERE missing_id = :missing_id");

            // Bind parameters to the SQL query
            $stmt->bindParam(':missing_id', $missingId);
            $stmt->bindParam(':acc_number', $accNumber);
            $stmt->bindParam(':class_number', $classNumber);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author_id', $authorId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':copyright', $copyright);
            $stmt->bindParam(':image', $image); 

        
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Missing book updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update the missing book';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
           
            $_SESSION['error_message'] = 'Failed to update missing book. Error: ' . $e->getMessage();
        }

       
        header('Location: ../missing.php'); 
        exit();
    } else {
        
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: ../missing.php'); 
        exit();
    }
}
?>
