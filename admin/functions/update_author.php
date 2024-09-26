<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    $authorId = filter_var($_POST['author_id'], FILTER_SANITIZE_NUMBER_INT);
    $authorName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    if (!empty($authorId) && !empty($authorName)) {
        try {
            // Prepare the SQL statement for updating the author
            $stmt = $pdo->prepare("UPDATE author SET author = :author WHERE author_id = :author_id");

            // Bind parameters
            $stmt->bindParam(':author', $authorName);
            $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['success_message'] = 'Failed to update author';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update author. Error: ' . $e->getMessage();
        }

        header('Location: ../author.php');
        exit(); 
    } else {
        $_SESSION['error_message'] = 'Author ID or name cannot be empty.';
        header('Location: ../author.php'); 
        exit();
    }
}
?>
