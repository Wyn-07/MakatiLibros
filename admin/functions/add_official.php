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

                $oldData = "<p class='italic-data'>No old data is present because new data is being added.</p>";
                $newData = "<div class='container-officials'>
                    <div class='container-officials-image'>
                        <img src='../official_images/$imageName'
                            class='image' style='width: 100%; height: 100%; object-fit: cover;'>
                    </div>
                    <div class='container-officials-description'>
                        <div class='input-text officials-name'>$name</div>
                        <div class='input-text officials-title'>$title</div>
                    </div>
                </div>";

                $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                $page = "About Page Officials";
                $description_audit = "Added a new officials";

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
