<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Get and sanitize input values
    $delinquentId = filter_var($_POST['editDelinquentId'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['editStatus'], FILTER_SANITIZE_STRING);

    if (!empty($delinquentId) && !empty($status)) {
        try {
            // Prepare the SQL statement for updating the status based on delinquent_id
            $stmt = $pdo->prepare("UPDATE delinquent SET status = :status WHERE delinquent_id = :delinquent_id");

            // Bind parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':delinquent_id', $delinquentId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Status updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update status';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update status. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect to the delinquent page
        header('Location: ../delinquent.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Delinquent ID or status cannot be empty.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../delinquent.php');
        exit();
    }
}
?>
