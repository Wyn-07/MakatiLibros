<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Initialize $imageName with a default image
    $imageName = 'no_image.png';

    // Process the image upload
    if (isset($_FILES['image_news']) && $_FILES['image_news']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image_news'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time for unique image name
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "news_title_date_time"
        $imageName = 'news_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../news_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../news.php');
            exit();
        }
    }

    // Check if required fields are empty
    if (!empty($title) && !empty($date) && !empty($description)) {
        try {
            // Prepare the SQL statement for inserting a new news item
            $stmt = $pdo->prepare("INSERT INTO news (title, date, description, image)
                                   VALUES (:title, :date, :description, :image)");

            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image', $imageName);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'News added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add news.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add news. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../news.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Title, date, and description cannot be empty.';
        header('Location: ../news.php');
        exit();
    }
}
?>
