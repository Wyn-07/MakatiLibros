<?php
session_start();

include '../../connection.php';

date_default_timezone_set('Asia/Manila');

if (isset($_POST['submit'])) {
    // Sanitize the borrow_id input
    $borrowID = filter_var($_POST['borrowID'], FILTER_SANITIZE_NUMBER_INT);

    $oldStatus = "<p class='italic-data'>No old data is present because new data is being added.</p>";
    $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
    $page = "Transaction Borrow";
    $description = "Added delinquent record for Borrow ID $borrowID";

    // Generate current date and time
    $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


    if (!empty($borrowID)) {
        try {
            // Check if the borrow ID already exists in the delinquent table
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM delinquent WHERE borrow_id = :borrow_id");
            $checkStmt->bindParam(':borrow_id', $borrowID, PDO::PARAM_INT);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                // Borrow ID already exists
                $_SESSION['error_message'] = 'Borrow ID already exists in delinquent records.';
                $_SESSION['error_display'] = 'flex';
            } else {
                // Prepare the SQL statement for insertion
                $stmt = $pdo->prepare("
                    INSERT INTO delinquent (borrow_id, status) 
                    VALUES (:borrow_id, :status)
                ");

                // Bind parameters
                $status = 'Unresolved'; // Default status
                $stmt->bindParam(':borrow_id', $borrowID, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);

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
                    $_SESSION['error_message'] = 'Failed to add delinquent record.';
                    $_SESSION['error_display'] = 'flex';
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add delinquent. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        header('Location: ../transactions.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Borrow ID cannot be empty.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../transactions.php');
        exit();
    }
}
