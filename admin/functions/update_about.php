<?php
session_start();

date_default_timezone_set('Asia/Manila');

include '../../connection.php';

$librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
$adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

if (isset($_POST['submit'])) {
    // Sanitize and retrieve form data
    $vision = filter_var($_POST['vision'], FILTER_SANITIZE_STRING);
    $mission = filter_var($_POST['mission'], FILTER_SANITIZE_STRING);
    $history = filter_var($_POST['history'], FILTER_SANITIZE_STRING);

    // Initialize image variables
    $mission1ImageName = $mission2ImageName = $mission3ImageName = null;
    $vision1ImageName = $vision2ImageName = $vision3ImageName = null;

    // Retrieve current data from the database for comparison
    $oldDataQuery = "SELECT * FROM about WHERE about_id = 1";
    $oldDataStmt = $pdo->prepare($oldDataQuery);
    $oldDataStmt->execute();
    $oldData = $oldDataStmt->fetch(PDO::FETCH_ASSOC);

    if (!$oldData) {
        $_SESSION['error_message'] = "Failed to fetch current data for audit logging.";
        header('Location: ../about.php');
        exit();
    }

    // Helper function to process image uploads
    function processImage($fileKey, $prefix)
    {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES[$fileKey];
            $imageTmpName = $image['tmp_name'];
            $currentDateTime = date('Ymd_His');
            $imageName = $prefix . '_' . $currentDateTime . '.jpg';
            $targetDir = '../../about_images/';
            $targetFilePath = $targetDir . $imageName;

            if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                return $imageName;  // Return new image name if upload is successful
            }
        }
        return null;  // Return null if no image uploaded or error
    }

    // Update section
    $mission1ImageName = processImage('mission_image_1', 'mission_1');
    $mission2ImageName = processImage('mission_image_2', 'mission_2');
    $mission3ImageName = processImage('mission_image_3', 'mission_3');
    $vision1ImageName = processImage('vision_image_1', 'vision_1');
    $vision2ImageName = processImage('vision_image_2', 'vision_2');
    $vision3ImageName = processImage('vision_image_3', 'vision_3');

    // Prepare SQL update query dynamically
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

    // Prepare the update statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vision', $vision);
    $stmt->bindParam(':mission', $mission);
    $stmt->bindParam(':history', $history);

    if (!empty($mission1ImageName)) $stmt->bindParam(':mission_image_1', $mission1ImageName);
    if (!empty($mission2ImageName)) $stmt->bindParam(':mission_image_2', $mission2ImageName);
    if (!empty($mission3ImageName)) $stmt->bindParam(':mission_image_3', $mission3ImageName);
    if (!empty($vision1ImageName)) $stmt->bindParam(':vision_image_1', $vision1ImageName);
    if (!empty($vision2ImageName)) $stmt->bindParam(':vision_image_2', $vision2ImageName);
    if (!empty($vision3ImageName)) $stmt->bindParam(':vision_image_3', $vision3ImageName);

    // Execute the update
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Information updated successfully.';
        $_SESSION['success_display'] = 'flex';

        // Audit logging
        $changes = [];
        $fields = ['vision', 'mission', 'history', 'mission_image_1', 'mission_image_2', 'mission_image_3', 'vision_image_1', 'vision_image_2', 'vision_image_3'];

        // Modify the dynamic image field handling
        foreach ($fields as $field) {
            // Check if the field is an image
            if (strpos($field, 'image') !== false) {
                // Determine the corresponding image variable name dynamically
                $uploadedImageName = null;
                if ($field == 'mission_image_1') {
                    $uploadedImageName = $mission1ImageName;
                } elseif ($field == 'mission_image_2') {
                    $uploadedImageName = $mission2ImageName;
                } elseif ($field == 'mission_image_3') {
                    $uploadedImageName = $mission3ImageName;
                } elseif ($field == 'vision_image_1') {
                    $uploadedImageName = $vision1ImageName;
                } elseif ($field == 'vision_image_2') {
                    $uploadedImageName = $vision2ImageName;
                } elseif ($field == 'vision_image_3') {
                    $uploadedImageName = $vision3ImageName;
                }

                // Get the old image name from the database
                $oldImageName = $oldData[$field] ?? null;

                // Check if an image was uploaded and it differs from the old image
                if ($uploadedImageName && $uploadedImageName !== $oldImageName) {
                    // Add the change to the changes array
                    $changes[] = [
                        'field' => $field,
                        'old' => $oldImageName,
                        'new' => $uploadedImageName,
                    ];
                }
            } else {
                // Handle non-image fields (vision, mission, history)
                $newValue = $$field;
                $oldValue = $oldData[$field] ?? null;

                // Normalize values for comparison
                $newValueNormalized = $newValue ?? '';
                $oldValueNormalized = $oldValue ?? '';

                // Log the change if values differ
                if ($newValueNormalized !== $oldValueNormalized) {
                    $changes[] = [
                        'field' => $field,
                        'old' => $oldValueNormalized,
                        'new' => $newValueNormalized,
                    ];
                }
            }
        }


        // Insert audit log for each change detected
        $auditTable = $librarianId ? 'librarian_audit' : 'admin_audit';
        $userId = $librarianId ?: $adminId;

        foreach ($changes as $change) {
            $auditSql = "
        INSERT INTO $auditTable (
            date_time, old_data, new_data, " . ($librarianId ? "librarians_id" : "admin_id") . ", page, description
        ) VALUES (
            :date_time, :old_data, :new_data, :user_id, :page, :description
        )";

            $auditStmt = $pdo->prepare($auditSql);
            $currentAuditDate = date('Y-m-d H:i:s');
            $description = "Updated " . $change['field'];
            $page = 'About Page';

            $auditStmt->bindParam(':date_time', $currentAuditDate);
            $auditStmt->bindParam(':old_data', $change['old']);
            $auditStmt->bindParam(':new_data', $change['new']);
            $auditStmt->bindParam(':user_id', $userId);
            $auditStmt->bindParam(':page', $page);
            $auditStmt->bindParam(':description', $description);

            $auditStmt->execute();
        }
    } else {
        $_SESSION['error_message'] = 'Failed to update information.';
        $_SESSION['error_display'] = 'flex';
    }

    header('Location: ../about.php');
    exit();
}
