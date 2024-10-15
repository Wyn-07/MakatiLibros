<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $contactId = filter_var($_POST['editContactId'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['editTitle'], FILTER_SANITIZE_STRING);
    $contactNum = filter_var($_POST['editContactNum'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['editDescription'], FILTER_SANITIZE_STRING);

    // Initialize the image variable
    $imageName = null;

    // Process the image if a new one is uploaded
    if (isset($_FILES['edit_image_contact']) && $_FILES['edit_image_contact']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['edit_image_contact'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "contactid_title_date_time"
        $imageName = $contactId . '_' . preg_replace('/\s+/', '_', strtolower($title)) . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../contact_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../contact.php');
            exit();
        }
    }

    // Check if required fields are empty
    if (!empty($contactId) && !empty($title) && !empty($contactNum)) {
        try {
            // Prepare the SQL statement for updating the contact
            $sql = "UPDATE contact SET 
                        title = :title, 
                        contact = :contactNum, 
                        description = :description" .
                (!empty($imageName) ? ", image = :image" : "") .
                " WHERE contact_id = :contact_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':contactNum', $contactNum);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':contact_id', $contactId, PDO::PARAM_INT);

            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Contact updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update contact.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update contact. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../contact.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Contact ID, title, and contact number cannot be empty.';
        header('Location: ../contact.php');
        exit();
    }
}
?>
