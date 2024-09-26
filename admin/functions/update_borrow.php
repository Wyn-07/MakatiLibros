<?php
session_start();
include '../../connection.php'; 

if (isset($_POST['submit'])) {
    // Sanitize and validate input fields
    $bookId = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
    $patronId = filter_var($_POST['patron_id'], FILTER_VALIDATE_INT);
    $borrowId = filter_var($_POST['borrow_id'], FILTER_VALIDATE_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $accNum = filter_var($_POST['acc_num'], FILTER_SANITIZE_STRING);
    $classNum = filter_var($_POST['class_num'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    
    // Get today's date
    $returnDate = date('m/d/Y'); // Adjust format if needed

    if (!empty($bookId) && !empty($patronId) && !empty($borrowId) && !empty($status)) {
        try {
            // Check if the book has already been returned
            $statusCheck = $pdo->prepare("SELECT status FROM borrow WHERE borrow_id = :borrow_id");
            $statusCheck->bindParam(':borrow_id', $borrowId);
            $statusCheck->execute();
            $currentStatus = $statusCheck->fetchColumn();

            if ($currentStatus === 'Returned') {
                $_SESSION['error_message'] = 'The user has already returned the book.';
                $_SESSION['error_display'] = 'flex';
                header('Location: ../return.php');
                exit();
            }

            // Update the borrow record
            $stmt = $pdo->prepare("UPDATE borrow 
                                    SET book_id = :book_id, 
                                        patrons_id = :patrons_id, 
                                        status = :status, 
                                        return_date = :return_date 
                                    WHERE borrow_id = :borrow_id");

            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':patrons_id', $patronId);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':return_date', $returnDate);
            $stmt->bindParam(':borrow_id', $borrowId);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Borrow record updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update the borrow record.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update borrow record. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        header('Location: ../return.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'All fields are required.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../return.php');
        exit();
    }
}
?>
