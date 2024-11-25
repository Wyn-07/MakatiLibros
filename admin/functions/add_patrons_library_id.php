<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $patronName = filter_var($_POST['patron'], FILTER_SANITIZE_STRING);
    $guarantorName = filter_var($_POST['guarantor'], FILTER_SANITIZE_STRING);
    $patronId = filter_var($_POST['patron_id'], FILTER_SANITIZE_NUMBER_INT);
    $guarantorId = filter_var($_POST['guarantor_id'], FILTER_SANITIZE_NUMBER_INT);


    $dateIssued = date('Y-m-d'); // Current date
    $validUntil = date('Y-m-d', strtotime('+1 year')); // One year from today

    // Check if required fields are empty
    if (!empty($patronName) && !empty($guarantorName) && !empty($patronId) && !empty($guarantorId)) {
        try {
            // Prepare the SQL statement for inserting a new patron's library ID information
            $stmt = $pdo->prepare("INSERT INTO patrons_library_id (patrons_id, guarantor_id, date_issued, valid_until)
                                   VALUES (:patrons_id, :guarantor_id, :date_issued, :valid_until)");

            // Bind parameters
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);
            $stmt->bindParam(':guarantor_id', $guarantorId, PDO::PARAM_INT);
            $stmt->bindParam(':date_issued', $dateIssued);
            $stmt->bindParam(':valid_until', $validUntil);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Library ID created successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to create library ID.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to create library ID. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../library_id.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Patron name, guarantor name, and IDs cannot be empty.';
        header('Location: ../library_id.php');
        exit();
    }
}
?>
