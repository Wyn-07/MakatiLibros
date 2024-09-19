<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    $categoryId = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $categoryName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    if (!empty($categoryId) && !empty($categoryName)) {
        try {
            // Prepare the SQL statement for updating the category
            $stmt = $pdo->prepare("UPDATE category SET category = :category WHERE category_id = :category_id");

            // Bind parameters
            $stmt->bindParam(':category', $categoryName);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['success_message'] = 'Failed to update category';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update category. Error: ' . $e->getMessage();
        }

        header('Location: ../category.php');
        exit(); 
    } else {
        $_SESSION['error_message'] = 'category ID or name cannot be empty.';
        header('Location: ../category.php'); 
        exit();
    }
}
?>
