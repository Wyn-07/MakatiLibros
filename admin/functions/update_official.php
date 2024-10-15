<?php
session_start();
date_default_timezone_set('Asia/Manila');  

include '../../connection.php';

if (isset($_POST['save'])) {
    // Sanitize input data
    $officialId = filter_var($_POST['official_id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

    // Initialize image variable
    $imageName = null;

    // Process the image upload
    if (isset($_FILES['official_image']) && $_FILES['official_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['official_image'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "officialid_name_date_time"
        $imageName = $officialId . '_' . str_replace(' ', '_', $name) . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../official_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../about.php');
            exit();
        }
    }

    // Validate the required fields
    if (!empty($officialId) && !empty($name) && !empty($title)) {
        try {
            // Prepare the SQL update statement
            $sql = "UPDATE officials SET 
                        name = :name, 
                        title = :title" . 
                        (!empty($imageName) ? ", image = :image" : "") . 
                    " WHERE officials_id = :official_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':official_id', $officialId, PDO::PARAM_INT);

            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the update
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Official information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update official information.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update official information. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../about.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Official ID, name, and title cannot be empty.';
        header('Location: ../about.php');
        exit();
    }
}
?>
