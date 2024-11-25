<?php
// Include your database connection
include('../../connection.php'); 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all'])) {
    // SQL query to update all notifications to 'seen' = 'Yes'
    $stmt = $pdo->prepare("UPDATE notification SET seen = 'Yes' WHERE seen = 'No'");

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the notifications page to see the updated status
        header('Location: ../notification.php'); // Replace with your actual page URL
        exit();
    } else {
        echo 'Error updating notifications.';
    }
}
?>
