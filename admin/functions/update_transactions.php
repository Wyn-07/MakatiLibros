<?php
session_start();

include '../../connection.php';

date_default_timezone_set('Asia/Manila');

if (isset($_POST['submit'])) {
    $borrowId = filter_var($_POST['editBorrowId'], FILTER_SANITIZE_NUMBER_INT);
    $oldStatus = filter_var($_POST['editOldStatus'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['editStatus'], FILTER_SANITIZE_STRING);

    $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

    $page = "Transaction";
    $description = "Updated the status of Borrow ID $borrowId";

    // Generate current date and time
    $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32
    $currentDate = date('m/d/Y'); // e.g., 09/19/2024
    $currentTime = date('H:i:s'); // e.g., 23:58:32

    if (!empty($borrowId) && !empty($status)) {
        try {
            // Start SQL query
            $sql = "
                UPDATE borrow 
                SET status = :status";

            // Add fields based on the new status
            if ($status === "Pending") {
                $sql .= ", 
                    accepted_date = 'Pending', accepted_time = 'Pending',
                    borrow_date = 'Pending', borrow_time = 'Pending',
                    return_date = 'Pending', return_time = 'Pending'";
            } elseif ($status === "Accepted") {
                $sql .= ", 
                    accepted_date = :accepted_date, accepted_time = :accepted_time,
                    borrow_date = 'Pending', borrow_time = 'Pending',
                    return_date = 'Pending', return_time = 'Pending'";
            } elseif ($status === "Borrowed") {
                $sql .= ", 
                    borrow_date = :borrow_date, borrow_time = :borrow_time,
                    return_date = 'Pending', return_time = 'Pending'";
            } elseif ($status === "Returned") {
                $sql .= ", 
                    return_date = :return_date, return_time = :return_time";
            }

            $sql .= " WHERE borrow_id = :borrow_id";

            $stmt = $pdo->prepare($sql);

            // Bind common parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':borrow_id', $borrowId, PDO::PARAM_INT);

            // Bind parameters conditionally
            if ($status === "Accepted") {
                $stmt->bindParam(':accepted_date', $currentDate, PDO::PARAM_STR);
                $stmt->bindParam(':accepted_time', $currentTime, PDO::PARAM_STR);
            } elseif ($status === "Borrowed") {
                $stmt->bindParam(':borrow_date', $currentDate, PDO::PARAM_STR);
                $stmt->bindParam(':borrow_time', $currentTime, PDO::PARAM_STR);
            } elseif ($status === "Returned") {
                $stmt->bindParam(':return_date', $currentDate, PDO::PARAM_STR);
                $stmt->bindParam(':return_time', $currentTime, PDO::PARAM_STR);
            }

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

        header('Location: ../transactions.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Borrow ID or status cannot be empty.';
        header('Location: ../transactions.php');
        exit();
    }
}
