<?php
session_start(); // Ensure session is started


date_default_timezone_set('Asia/Manila');

include '../../connection.php'; 

try {
    // Get data from POST request (from the form)
    $bookId = $_POST['add_book_id'];
    $patronId = $_POST['add_patrons_id'];
    $status = $_POST['status'];

    $date = date('m/d/Y'); // Example: 09/19/2024

    // Prepare SQL statement for updating the existing record
    $stmt = $pdo->prepare('UPDATE favorites SET date = :date, status = :status WHERE book_id = :book_id AND patrons_id = :patrons_id');

    // Bind parameters
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    // Execute the update query
    $stmt->execute();

    // Check if the row was updated
    if ($stmt->rowCount() > 0) {
        // Redirect with success message
        $_SESSION['success_message'] = 'Add to favorite successfully';
        $_SESSION['success_display'] = 'flex';
    } else {
        // No rows were updated, insert a new record
        $insertStmt = $pdo->prepare('INSERT INTO favorites (book_id, patrons_id, date, status) VALUES (:book_id, :patrons_id, :date, :status)');
        $insertStmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $insertStmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);
        $insertStmt->bindParam(':date', $date, PDO::PARAM_STR);
        $insertStmt->bindParam(':status', $status, PDO::PARAM_STR);

        $insertStmt->execute();

        // Redirect with success message
        $_SESSION['success_message'] = 'Add to favorite successfully';
        $_SESSION['success_display'] = 'flex';
    }

} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error_message'] = 'Failed. Error: ' . $e->getMessage();
}

// Redirect back to the referring page or default to userpage.php
$referer = isset($_POST['referer']) ? $_POST['referer'] : '../userpage.php';
header('Location: ' . $referer);
exit;
?>
