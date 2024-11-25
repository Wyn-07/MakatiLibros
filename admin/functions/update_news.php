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
                
                $oldDate = filter_var($_POST['oldDate'], FILTER_SANITIZE_STRING);
                $oldTitle = filter_var($_POST['oldTitle'], FILTER_SANITIZE_STRING);
                $oldDescription = filter_var($_POST['oldDescription'], FILTER_SANITIZE_STRING);

                $oldImageName = filter_var($_POST['oldImageName'], FILTER_SANITIZE_STRING);

                $newImageName = isset($_FILES['edit_image_news']) && $_FILES['edit_image_news']['error'] === UPLOAD_ERR_OK
                    ? $imageName // If a new image is uploaded
                    : $oldImageName; // Use the old image if no new image is uploaded

                if ($oldName === $name && $oldTitle === $title && $imageName === null) {
                    $_SESSION['success_message'] = 'News information updated successfully. No changes detected.';
                    $_SESSION['success_display'] = 'flex';
                    // Redirect to the appropriate page
                    header('Location: ../news.php');
                    exit();
                }

                $oldData = "<div class='container-form-official'>
                                <div class='container-news-image-modal'>
                                    <img src='../news_images/$oldImageName' class='image'>
                                </div>

                                <div class='container-input-100'>
                                  <label for='title'>Title</label>
                                  <div class='input-text'> $oldTitle </div>
                                </div>

                                <div class='container-input-100'>
                                  <label for='date'>Date</label>
                                  <div class='input-text'> $oldDate </div>
                                </div>

                                <div class='container-input-100'>
                                  <label for='description'>Description</label>
                                  <div class='textarea-news'> $oldDescription </div>
                                </div>
                            </div>>";

                $newData = "<div class='container-form-official'>
                                <div class='container-news-image-modal'>
                                    <img src='../news_images/$newImageName' class='image'>
                                </div>
                                <div class='container-input-100'>
                                  <label for='title'>Title</label>
                                  <div class='input-text'> $title </div>
                                </div>
                                <div class='container-input-100'>
                                  <label for='date'>Date</label>
                                  <div class='input-text'> $date </div>
                                </div>
                                <div class='container-input-100'>
                                  <label for='description'>Description</label>
                                  <div class='textarea-news'> $description </div>
                                </div>
                            </div>";

                $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                $page = "News Page";
                $description_audit = "Updated information of News ID " . $newsId;

                $currentAuditDate = date('Y-m-d H:i:s'); 


                // Prepare the audit log insertion
                $auditSql = "";
                $auditStmt = null;

                if ($librarianId) {
                    $auditSql = "
                        INSERT INTO librarian_audit (
                            date_time, old_data, new_data, librarians_id, page, description
                        ) VALUES (
                            :date_time, :old_data, :new_data, :librarians_id, :page, :description
                        )";
                    $auditStmt = $pdo->prepare($auditSql);
                    $auditStmt->bindParam(':librarians_id', $librarianId, PDO::PARAM_INT);
                } elseif ($adminId) {
                    $auditSql = "
                        INSERT INTO admin_audit (
                            date_time, old_data, new_data, admin_id, page, description
                        ) VALUES (
                            :date_time, :old_data, :new_data, :admin_id, :page, :description
                        )";
                    $auditStmt = $pdo->prepare($auditSql);
                    $auditStmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
                }

                if ($auditStmt) {
                    $auditStmt->bindParam(':date_time', $currentAuditDate, PDO::PARAM_STR);
                    $auditStmt->bindParam(':old_data', $oldData, PDO::PARAM_STR);
                    $auditStmt->bindParam(':new_data', $newData, PDO::PARAM_STR);
                    $auditStmt->bindParam(':page', $page, PDO::PARAM_STR);
                    $auditStmt->bindParam(':description', $description_audit, PDO::PARAM_STR);

                    if (!$auditStmt->execute()) {
                        throw new Exception('Failed to insert audit log entry.');
                    }
                } else {
                    throw new Exception('Neither librarian nor admin ID is available.');
                }


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
