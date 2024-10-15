<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and retrieve form data
    $vision = filter_var($_POST['vision']);
    $mission = filter_var($_POST['mission']); 
    $history = filter_var($_POST['history']); 

    // Initialize image variables
    $mission1ImageName = null;
    $mission2ImageName = null;
    $mission3ImageName = null;

    $vision1ImageName = null;
    $vision2ImageName = null;
    $vision3ImageName = null;

    // Process the mission image
    if (isset($_FILES['mission_image_1']) && $_FILES['mission_image_1']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['mission_image_1'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');
        $mission1ImageName = 'mission_1_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $mission1ImageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload mission image.";
            header('Location: ../about.php');
            exit();
        }
    }

    if (isset($_FILES['mission_image_2']) && $_FILES['mission_image_2']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['mission_image_2'];
        $imageTmpName = $image['tmp_name'];

        $currentDateTime = date('Ymd_His');
        $mission2ImageName = 'mission_2_' . $currentDateTime . '.jpg';

        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $mission2ImageName;

        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload mission image.";
            header('Location: ../about.php');
            exit();
        }
    }


    if (isset($_FILES['mission_image_3']) && $_FILES['mission_image_3']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['mission_image_3'];
        $imageTmpName = $image['tmp_name'];

        $currentDateTime = date('Ymd_His');
        $mission3ImageName = 'mission_3_' . $currentDateTime . '.jpg';

        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $mission3ImageName;

        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload mission image.";
            header('Location: ../about.php');
            exit();
        }
    }

    // Process the vision image
    if (isset($_FILES['vision_image_1']) && $_FILES['vision_image_1']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['vision_image_1'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');
        $vision1ImageName = 'vision_1_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $vision1ImageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload vision image.";
            header('Location: ../about.php');
            exit();
        }
    }

    if (isset($_FILES['vision_image_2']) && $_FILES['vision_image_2']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['vision_image_2'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');
        $vision2ImageName = 'vision_2_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $vision2ImageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload vision image.";
            header('Location: ../about.php');
            exit();
        }
    }


    if (isset($_FILES['vision_image_3']) && $_FILES['vision_image_3']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['vision_image_3'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');
        $vision3ImageName = 'vision_3_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../about_images/';
        $targetFilePath = $targetDir . $vision3ImageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = "Failed to upload vision image.";
            header('Location: ../about.php');
            exit();
        }
    }

    // Prepare the SQL update statement
    $sql = "UPDATE about SET 
                history = :history, 
                vision = :vision, 
                mission = :mission" .
                (!empty($mission1ImageName) ? ", mission_image_1 = :mission_image_1" : "") .
                (!empty($mission2ImageName) ? ", mission_image_2 = :mission_image_2" : "") .
                (!empty($mission3ImageName) ? ", mission_image_3 = :mission_image_3" : "") .
                (!empty($vision1ImageName) ? ", vision_image_1 = :vision_image_1" : "") .
                (!empty($vision2ImageName) ? ", vision_image_2 = :vision_image_2" : "") .
                (!empty($vision3ImageName) ? ", vision_image_3 = :vision_image_3" : "") .

            " WHERE about_id = 1";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind the basic parameters
        $stmt->bindParam(':vision', $vision);
        $stmt->bindParam(':mission', $mission);
        $stmt->bindParam(':history', $history);


        // Bind the image parameters only if new images were uploaded
        if (!empty($mission1ImageName)) {
            $stmt->bindParam(':mission_image_1', $mission1ImageName);
        }
        if (!empty($mission2ImageName)) {
            $stmt->bindParam(':mission_image_2', $mission2ImageName);
        }
        if (!empty($mission3ImageName)) {
            $stmt->bindParam(':mission_image_3', $mission3ImageName);
        }
        if (!empty($vision1ImageName)) {
            $stmt->bindParam(':vision_image_1', $vision1ImageName);
        }
        if (!empty($vision2ImageName)) {
            $stmt->bindParam(':vision_image_2', $vision2ImageName);
        }
        if (!empty($vision3ImageName)) {
            $stmt->bindParam(':vision_image_3', $vision3ImageName);
        }

        // Execute the update
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Mission and vision information updated successfully.';
            $_SESSION['success_display'] = 'flex';
        } else {
            $_SESSION['error_message'] = 'Failed to update mission and vision information.';
            $_SESSION['error_display'] = 'flex';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Failed to update information. Error: ' . $e->getMessage();
    }

    header('Location: ../about.php');
    exit();
}
