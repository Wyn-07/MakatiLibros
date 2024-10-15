<?php
session_start();
include '../../connection.php';

if (isset($_POST['official_id'])) {
    // Sanitize the official_id
    $officialId = filter_var($_POST['official_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($officialId)) {
        try {
            // Prepare SQL delete query
            $sql = "DELETE FROM officials WHERE officials_id = :official_id";
            $stmt = $pdo->prepare($sql);

            // Bind the official_id parameter
            $stmt->bindParam(':official_id', $officialId, PDO::PARAM_INT);

            // Execute the delete query
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Official deleted successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to delete official.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete official. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect back to the previous page
        header('Location: ../about.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid official ID.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../about.php');
        exit();
    }
}
