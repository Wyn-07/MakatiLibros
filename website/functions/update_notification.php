<?php

include('../../connection.php'); 

if (isset($_POST['submit'])) {
    $notifId = $_POST['notif_id']; // The notification ID
    $currentSeen = $_POST['current_seen']; // The current 'seen' status (Yes or No)

    // Toggle the 'seen' status
    $newSeen = ($currentSeen === 'Yes') ? 'No' : 'Yes';

    // Prepare SQL to update the 'seen' status
    $stmt = $pdo->prepare("UPDATE notification SET seen = :newSeen WHERE notif_id = :notifId");
    $stmt->bindParam(':newSeen', $newSeen);
    $stmt->bindParam(':notifId', $notifId);

    // Execute the update query
    if ($stmt->execute()) {
        // Redirect back to the page to refresh the notification state
        header('Location: ../notification.php');
        exit();
    } else {
        echo 'Error updating notification.';
    }
}


?>