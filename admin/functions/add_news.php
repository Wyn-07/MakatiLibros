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

                $oldData = "<p class='italic-data'>No old data is present because new data is being added.</p>";
                $newData = "<div class='container-form-official'>
                                <div class='container-news-image-modal'>
                                    <img src='../news_images/$imageName' class='image'>
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
                $description_audit = "Added a new news";

                $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


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
