<?php
session_start();

include '../../connection.php';

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST['submit'])) {
    $borrowId = filter_var($_POST['editBorrowId'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['editStatus'], FILTER_SANITIZE_STRING); // Corrected to 'editStatus'

    // Generate current date and time in the required format
    $currentDate = date('m/d/Y'); // e.g., 09/19/2024
    $currentTime = date('H:i:s'); // e.g., 23:58:32

    // Initialize default values for each date/time field
    $borrowDate = "Pending";
    $borrowTime = "Pending";
    $returnDate = "Pending";
    $returnTime = "Pending";

    // Set values based on status
    if ($status === "Borrowing") {
        $borrowDate = $currentDate;
        $borrowTime = $currentTime;
        // Return date and time remain "Pending"
    } elseif ($status === "Returned") {
        // Do not change borrow date/time if status is 'Returned'
        $returnDate = $currentDate;
        $returnTime = $currentTime;
    }

    if (!empty($borrowId) && !empty($status)) {
        try {
            // Prepare the SQL statement for updating the record
            $stmt = $pdo->prepare("UPDATE borrow 
                                   SET status = :status, 
                                       borrow_date = IF(:status = 'Returned', borrow_date, :borrow_date), 
                                       borrow_time = IF(:status = 'Returned', borrow_time, :borrow_time),
                                       return_date = :return_date, 
                                       return_time = :return_time 
                                   WHERE borrow_id = :borrow_id");

            // Bind parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':borrow_date', $borrowDate, PDO::PARAM_STR);
            $stmt->bindParam(':borrow_time', $borrowTime, PDO::PARAM_STR);
            $stmt->bindParam(':return_date', $returnDate, PDO::PARAM_STR);
            $stmt->bindParam(':return_time', $returnTime, PDO::PARAM_STR);
            $stmt->bindParam(':borrow_id', $borrowId, PDO::PARAM_INT);

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

        header('Location: ../transactions.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Borrow ID or status cannot be empty.';
        header('Location: ../transactions.php');
        exit();
    }
}
