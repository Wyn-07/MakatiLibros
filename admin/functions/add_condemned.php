<?php
session_start();

include '../../connection.php'; 

if (isset($_POST['submit'])) {
    // Sanitize and validate input fields
    $bookId = filter_var($_POST['delete_book_id'], FILTER_VALIDATE_INT); // Get the book ID

    // Validate the book_id
    if (!empty($bookId)) {
        try {
            // Set timezone to Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $currentDate = date('Y-m-d'); // Get today's date

            // Prepare the SQL statement to insert the condemned book
            $stmt = $pdo->prepare("INSERT INTO condemned (book_id, date) VALUES (:book_id, :date)");

            // Bind parameters to the SQL query
            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':date', $currentDate);

            // Execute the statement and check if successful
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Condemned book added successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add the condemned book';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            // Handle SQL errors
            $_SESSION['error_message'] = 'Failed to add condemned book. Error: ' . $e->getMessage();
        }

        // Redirect back to the form or a success page
        header('Location: ../book-list.php'); 
        exit();
    } else {
        // Set an error message if the book_id is empty or invalid
        $_SESSION['error_message'] = 'Please provide a valid book ID.';
        header('Location: ../book-list.php'); 
        exit();
    }
}
?>
