<?php
session_start(); // Start the session

include '../../connection.php'; // Assuming this file sets up the PDO connection as $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $patrons_id = $_POST['patrons_id'];
    $rating = $_POST['rate'];

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
                // Update the rating
                $query = "UPDATE ratings SET ratings = :ratings WHERE book_id = :book_id AND patrons_id = :patrons_id";
            } else {
                // Insert a new rating
                $query = "INSERT INTO ratings (book_id, patrons_id, ratings) VALUES (:book_id, :patrons_id, :ratings)";
            }

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
            $stmt->bindParam(':ratings', $rating, PDO::PARAM_INT);

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
