<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $newsId = filter_var($_POST['editNewsId'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['editTitle'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['editDate'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['editDescription'], FILTER_SANITIZE_STRING);

    // Initialize the image variable
    $imageName = null;

    // Process the image if a new one is uploaded
    if (isset($_FILES['edit_image_news']) && $_FILES['edit_image_news']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['edit_image_news'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "newsid_title_date_time"
        $imageName = $newsId . '_' . preg_replace('/\s+/', '_', strtolower($title)) . '_' . $currentDateTime . '.jpg';

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
    if (!empty($newsId) && !empty($title) && !empty($date)) {
        try {
            // Prepare the SQL statement for updating the news
            $sql = "UPDATE news SET 
                        title = :title, 
                        date = :date, 
                        description = :description" .
                (!empty($imageName) ? ", image = :image" : "") .
                " WHERE news_id = :news_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':news_id', $newsId, PDO::PARAM_INT);

            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'News updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update news.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update news. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../news.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'News ID, title, and date cannot be empty.';
        header('Location: ../news.php');
        exit();
    }
}
?>
