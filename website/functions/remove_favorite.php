<?php
session_start(); 

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=librodb', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get data from POST request (from the form)
    $bookId = $_POST['remove_book_id'];
    $patronId = $_POST['remove_patrons_id'];
    $status = $_POST['status'];

    // Get today's date in the desired format
    $date = date('F d, Y'); 

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
        $_SESSION['success_message'] = 'Remove to favorite successfully';
        $_SESSION['success_display'] = 'flex';
    } else {
        // No rows were updated (e.g., record might not exist)
        $_SESSION['error_message'] = 'No record found to update.';
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
