<?php
session_start();

date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['official_id'])) {
    // Sanitize the official_id
    $officialId = filter_var($_POST['official_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($officialId)) {
        try {
            // Fetch the official's data before deleting
            $fetchSql = "SELECT name, title, image FROM officials WHERE officials_id = :official_id";
            $fetchStmt = $pdo->prepare($fetchSql);
            $fetchStmt->bindParam(':official_id', $officialId, PDO::PARAM_INT);
            $fetchStmt->execute();

            // Check if the record exists
            $official = $fetchStmt->fetch(PDO::FETCH_ASSOC);

            if ($official) {
                // Store the fetched data
                $name = $official['name'];
                $title = $official['title'];
                $image = $official['image'];

                // Prepare SQL delete query
                $sql = "DELETE FROM officials WHERE officials_id = :official_id";
                $stmt = $pdo->prepare($sql);

                // Bind the official_id parameter
                $stmt->bindParam(':official_id', $officialId, PDO::PARAM_INT);

                // Execute the delete query
                if ($stmt->execute()) {
                    $oldData = "<div class='container-officials'>
                        <div class='container-officials-image'>
                            <img src='../official_images/$image'
                                class='image' style='width: 100%; height: 100%; object-fit: cover;'>
                        </div>
                        <div class='container-officials-description'>
                            <div class='input-text officials-name'>$name</div>
                            <div class='input-text officials-title'>$title</div>
                        </div>
                    </div>";
                    $newData = "<p class='italic-data'>No new data is present because old data is being deleted.</p>";

                    $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                    $page = "About Page Officials";
                    $description_audit = "Deleted Official ID " . $officialId;

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

                    $_SESSION['success_message'] = 'Official deleted successfully.';
                    $_SESSION['success_display'] = 'flex';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete official.';
                    $_SESSION['error_display'] = 'flex';
                }
            } else {
                $_SESSION['error_message'] = 'Official not found.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete official. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect back to the previous page
        header('Location: ../about.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid official ID.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../about.php');
        exit();
    }
}
