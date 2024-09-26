<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    $authorName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    if (!empty($authorName)) {
        try {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO author (author) VALUES (:author)");

            // Bind parameters
            $stmt->bindParam(':author', $authorName);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Added successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['success_message'] = 'Failed to add author';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add author. Error: ' . $e->getMessage();
        }

        header('Location: ../author.php');
        exit(); 
    } else {
        $_SESSION['error_message'] = 'Author name cannot be empty.';
        header('Location: ../author.php'); 
        exit();
    }
}
?>
