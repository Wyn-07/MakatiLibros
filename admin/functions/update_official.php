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
                
                $oldName = filter_var($_POST['oldName'], FILTER_SANITIZE_STRING);
                $oldTitle = filter_var($_POST['oldTitle'], FILTER_SANITIZE_STRING);
                $oldImageName = filter_var($_POST['oldImageName'], FILTER_SANITIZE_STRING);

                $newImageName = isset($_FILES['official_image']) && $_FILES['official_image']['error'] === UPLOAD_ERR_OK
                ? $imageName 
                : $oldImageName;

                if ($oldName === $name && $oldTitle === $title && $imageName === null) {
                    $_SESSION['success_message'] = 'Official information updated successfully. No changes detected.';
                    $_SESSION['success_display'] = 'flex';
                    // Redirect to the appropriate page
                    header('Location: ../about.php');
                    exit();
                }

                $oldData = "<div class='container-officials'>
                    <div class='container-officials-image'>
                        <img src='../official_images/$oldImageName'
                            class='image' style='width: 100%; height: 100%; object-fit: cover;'>
                    </div>
                    <div class='container-officials-description'>
                        <div class='input-text officials-name'>$oldName</div>
                        <div class='input-text officials-title'>$oldTitle</div>
                    </div>
                </div>";

                $newData = "<div class='container-officials'>
                    <div class='container-officials-image'>
                        <img src='../official_images/$newImageName'
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
                $description_audit = "Updated information of Official ID " . $officialId;

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
