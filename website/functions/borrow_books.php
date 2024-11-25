<?php
session_start();

// Include the database connection
include '../../connection.php';

$bookId = $_POST['book_id'];
$userId = $_POST['patrons_id'];
$status = $_POST['borrow_status'];

try {
    // Check if the user has 3 or more pending borrows
    $checkPendingStmt = $pdo->prepare('SELECT COUNT(*) FROM borrow WHERE patrons_id = :patrons_id AND status = "Pending"');
    $checkPendingStmt->bindParam(':patrons_id', $userId, PDO::PARAM_INT);
    $checkPendingStmt->execute();
    $pendingCount = $checkPendingStmt->fetchColumn();

    if ($pendingCount >= 3) {
        // Set error message if user has 3 or more pending borrows
        $_SESSION['error_message'] = 'You have already reached the maximum limit of 3 pending borrows. Please return some items before borrowing more.';
        $_SESSION['error_display'] = 'flex';
    } else {
        // Check if the user has 3 or more active borrows with status "Borrowing"
        $checkBorrowingStmt = $pdo->prepare('SELECT COUNT(*) FROM borrow WHERE patrons_id = :patrons_id AND status = "Borrowed"');
        $checkBorrowingStmt->bindParam(':patrons_id', $userId, PDO::PARAM_INT);
        $checkBorrowingStmt->execute();
        $borrowingCount = $checkBorrowingStmt->fetchColumn();

        if ($borrowingCount >= 3) {
            // Set error message if user has 3 or more active borrows with "Borrowing" status
            $_SESSION['error_message'] = 'You have reached the maximum borrowing limit of 3 active books. Please return some books before borrowing more.';
            $_SESSION['error_display'] = 'flex';
        } else {
            // Check if the user is marked as delinquent
            $delinquentCheckStmt = $pdo->prepare('SELECT COUNT(*) FROM delinquent WHERE borrow_id IN (SELECT borrow_id FROM borrow WHERE patrons_id = :patrons_id) AND status = "Unresolved"');
            $delinquentCheckStmt->bindParam(':patrons_id', $userId, PDO::PARAM_INT);
            $delinquentCheckStmt->execute();
            $isDelinquent = $delinquentCheckStmt->fetchColumn();

            if ($isDelinquent > 0) {
                // Set error message if the user is marked as delinquent
                $_SESSION['error_message'] = 'You are unable to borrow books from the library because you have been marked as delinquent for not returning borrowed books. Resolve it by returning the book.';
                $_SESSION['error_display'] = 'flex';
            } else {
                // Prepare SQL statement with borrow_date, borrow_time, return_date, and return_time set to 'Pending'
                $stmt = $pdo->prepare('INSERT INTO borrow (book_id, patrons_id, status, accepted_date, accepted_time, borrow_date, borrow_time, return_date, return_time) VALUES (:book_id, :patrons_id, :status, "Pending", "Pending", "Pending", "Pending", "Pending", "Pending")');

                // Bind parameters
                $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
                $stmt->bindParam(':patrons_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);

                $stmt->execute();

                // Set success message
                $_SESSION['success_message'] = 'Submitted successfully. Please wait for the approval of your book borrowing request.';
                $_SESSION['success_display'] = 'flex';
                $_SESSION['success_info'] = 'flex';
            }
        }
    }
} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error_message'] = 'Failed to borrow the book. Error: ' . $e->getMessage();
}

// Redirect back to the referring page or default to userpage.php
$referer = isset($_POST['referer']) ? $_POST['referer'] : '../userpage.php';
header('Location: ' . $referer);
exit;
