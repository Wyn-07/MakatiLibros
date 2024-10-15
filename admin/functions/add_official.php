<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

    // Initialize $imageName with default image
    $imageName = 'default-image.jfif';

    // Process the image upload
    if (isset($_FILES['image_official']) && $_FILES['image_official']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image_official'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time for unique image name
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "official_lastname_date_time"
        $imageName = $name . '_' . $currentDateTime . '.jpg';

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

    // Check if required fields are empty
    if (!empty($name) && !empty($title)) {
        try {
            // Prepare the SQL statement for inserting a new official's information
            $stmt = $pdo->prepare("INSERT INTO officials (name, title, image)
                                   VALUES (:name, :title, :image)");

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':image', $imageName);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Official added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add official.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add official. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../about.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Name and title cannot be empty.';
        header('Location: ../about.php');
        exit();
    }
}
?>
