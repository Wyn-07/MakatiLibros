<?php
session_start();
include '../../connection.php';

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST['submit'])) {
    // Sanitize and validate input fields
    $bookId = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
    $patronId = filter_var($_POST['patron_id'], FILTER_VALIDATE_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $borrowDate = date('m/d/Y'); // Format: m/d/Y
    $borrowTime = date('H:i:s'); // Format: H:i:s
    $returnDate = "Pending";
    $returnTime = "Pending";

    if (!empty($bookId) && !empty($patronId) && !empty($status)) {
        try {
            // Check borrowing limit
            $limitCheck = $pdo->prepare("SELECT COUNT(*) FROM borrow WHERE patrons_id = :patrons_id AND status = 'Borrowing'");
            $limitCheck->bindParam(':patrons_id', $patronId);
            $limitCheck->execute();
            $borrowedCount = $limitCheck->fetchColumn();

            if ($borrowedCount >= 5) {
                $_SESSION['error_message'] = 'The borrower has reached the limit of 5 books that can be borrowed.';
                $_SESSION['error_display'] = 'flex'; // Set error display to flex
                header('Location: ../transactions.php');
                exit();
            }

            // Check if the user has not yet returned the selected book
            $userReturnCheck = $pdo->prepare("SELECT COUNT(*) FROM borrow WHERE patrons_id = :patrons_id AND book_id = :book_id AND status = 'Borrowing'");
            $userReturnCheck->bindParam(':patrons_id', $patronId);
            $userReturnCheck->bindParam(':book_id', $bookId);
            $userReturnCheck->execute();
            $hasNotReturned = $userReturnCheck->fetchColumn();

            if ($hasNotReturned > 0) {
                $_SESSION['error_message'] = 'Borrower has not yet returned the selected book.';
                $_SESSION['error_display'] = 'flex'; // Set error display to flex
                header('Location: ../transactions.php');
                exit();
            }

            // Check if the book is already borrowed
            $bookCheck = $pdo->prepare("SELECT COUNT(*) FROM borrow WHERE book_id = :book_id AND status = 'Borrowing'");
            $bookCheck->bindParam(':book_id', $bookId);
            $bookCheck->execute();
            $isBookBorrowed = $bookCheck->fetchColumn();

            if ($isBookBorrowed > 0) {
                $_SESSION['error_message'] = 'The book you are trying to borrow has not yet been returned by another borrower.';
                $_SESSION['error_display'] = 'flex'; // Set error display to flex
                header('Location: ../transactions.php');
                exit();
            }

            // Insert the borrow record
            $stmt = $pdo->prepare("INSERT INTO borrow (book_id, patrons_id, status, borrow_date, return_date, borrow_time, return_time) 
                                   VALUES (:book_id, :patrons_id, :status, :borrow_date, :return_date, :borrow_time, :return_time)");

            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':patrons_id', $patronId);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':borrow_date', $borrowDate);
            $stmt->bindParam(':return_date', $returnDate);
            $stmt->bindParam(':borrow_time', $borrowTime);
            $stmt->bindParam(':return_time', $returnTime);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Borrow record added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add the borrow record.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add borrow record. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        header('Location: ../transactions.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'All fields are required.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../transactions.php');
        exit();
    }
}

?>