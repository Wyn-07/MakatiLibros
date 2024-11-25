<?php
session_start();

include '../../connection.php';

date_default_timezone_set('Asia/Manila');

if (isset($_POST['submit'])) {
    // Get and sanitize input values
    $delinquentId = filter_var($_POST['editDelinquentId'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['editStatus'], FILTER_SANITIZE_STRING);

    $oldStatus = filter_var($_POST['editOldStatus'], FILTER_SANITIZE_STRING);
    $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

    $page = "Delinquent";
    $description = "Updated the status of Delinquent ID $delinquentId";

    // Generate current date and time
    $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


    if (!empty($delinquentId) && !empty($status)) {
        try {
            // Prepare the SQL statement for updating the status based on delinquent_id
            $stmt = $pdo->prepare("UPDATE delinquent SET status = :status WHERE delinquent_id = :delinquent_id");

            // Bind parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':delinquent_id', $delinquentId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {

                // Check if old and new status are different
                if ($oldStatus !== $status) {
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
                        $auditStmt->bindParam(':old_data', $oldStatus, PDO::PARAM_STR);
                        $auditStmt->bindParam(':new_data', $status, PDO::PARAM_STR);
                        $auditStmt->bindParam(':page', $page, PDO::PARAM_STR);
                        $auditStmt->bindParam(':description', $description, PDO::PARAM_STR);

                        if (!$auditStmt->execute()) {
                            throw new Exception('Failed to insert audit log entry.');
                        }
                    } else {
                        throw new Exception('Neither librarian nor admin ID is available.');
                    }
                }

                $_SESSION['success_message'] = 'Status updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update status';
                $_SESSION['error_display'] = 'flex';
            }
            
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update status. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect to the delinquent page
        header('Location: ../delinquent.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Delinquent ID or status cannot be empty.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../delinquent.php');
        exit();
    }
}
