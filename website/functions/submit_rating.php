<?php
session_start(); // Start the session

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

include '../../connection.php'; // Assuming this file sets up the PDO connection as $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $patrons_id = $_POST['patrons_id'];
    $rating = $_POST['rate'];

    // Get current date and time
    $current_date = date('m/d/Y'); // Format: m/d/Y
    $current_time = date('H:i:s'); // Format: H:i:s

    // Validate inputs
    if (!empty($book_id) && !empty($patrons_id) && !empty($rating)) {
        try {
            // Check if the user has already rated the book
            $checkQuery = "SELECT rating_id FROM ratings WHERE book_id = :book_id AND patrons_id = :patrons_id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $checkStmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Update the rating, date, and time
                $query = "UPDATE ratings SET ratings = :ratings, date = :date, time = :time WHERE book_id = :book_id AND patrons_id = :patrons_id";
            } else {
                // Insert a new rating with date and time
                $query = "INSERT INTO ratings (book_id, patrons_id, ratings, date, time) VALUES (:book_id, :patrons_id, :ratings, :date, :time)";
            }

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
            $stmt->bindParam(':ratings', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':date', $current_date, PDO::PARAM_STR);
            $stmt->bindParam(':time', $current_time, PDO::PARAM_STR); // Bind time parameter

            // Execute the statement
            if ($stmt->execute()) {
                // Store the message and display status in session
                $_SESSION['success_message'] = "Rated successfully.";
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = "Error executing query.";
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }
    } else {
        $_SESSION['error_message'] = "Invalid input.";
        $_SESSION['error_display'] = 'flex';
    }

    // Redirect back to the referring page or default to userpage.php
    $referer = isset($_POST['referer']) ? $_POST['referer'] : '../userpage.php';
    header('Location: ' . $referer);
    exit;
}
?>
