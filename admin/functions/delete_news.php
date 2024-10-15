<?php
session_start();
include '../../connection.php';

if (isset($_POST['news_id'])) {
    // Sanitize the official_id
    $officialId = filter_var($_POST['news_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($officialId)) {
        try {
            // Prepare SQL delete query
            $sql = "DELETE FROM news WHERE news_id = :news_id";
            $stmt = $pdo->prepare($sql);

            // Bind the official_id parameter
            $stmt->bindParam(':news_id', $officialId, PDO::PARAM_INT);

            // Execute the delete query
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'News deleted successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to delete news.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete news. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect back to the previous page
        header('Location: ../news.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid News ID.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../news.php');
        exit();
    }
}
