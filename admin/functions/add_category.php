<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    $categoryName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    if (!empty($categoryName)) {
        try {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO category (category) VALUES (:category)");

            // Bind parameters
            $stmt->bindParam(':category', $categoryName);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Added successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['success_message'] = 'Failed to add category';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add category. Error: ' . $e->getMessage();
        }

        header('Location: ../category.php');
        exit(); 
    } else {
        $_SESSION['error_message'] = 'category name cannot be empty.';
        header('Location: ../category.php'); 
        exit();
    }
}
?>
