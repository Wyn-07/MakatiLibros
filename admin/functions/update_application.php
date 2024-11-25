<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $patronId = filter_var($_POST['edit_patron_id'], FILTER_SANITIZE_NUMBER_INT);

    $guarantorId = filter_var($_POST['edit_guarantor_id'], FILTER_SANITIZE_NUMBER_INT);

    $oldStatus = filter_var($_POST['editOldStatus'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['editStatus'], FILTER_SANITIZE_STRING);

    $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

    $page = "Application";
    $description = "Updated the status of Patron ID $patronId";

    // Generate current date and time
    $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


    if ($status === 'Pending') {
        header('Location: ../application.php');
        exit();
    } else if ($status === 'Approved') {
        $reason = "Meet Requirements"; // Override reason for "Approved"
    } else {
        $reason = filter_var($_POST['editReason'], FILTER_SANITIZE_STRING); // Use form reason for other statuses
    }

    try {
        // Check if the status is "Rejected"
        if ($status === 'Rejected') {
            // Prepare the SQL statement for updating only the patron's application status and reason
            $sql = "UPDATE patrons SET 
                            application_status = :application_status, 
                            application_status_reason = :application_status_reason
                    WHERE patrons_id = :patrons_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':application_status', $status);
            $stmt->bindParam(':application_status_reason', $reason);
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {

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

                    if ($auditStmt->execute()) {
                        $_SESSION['success_message'] = 'Patron status updated successfully.';
                        $_SESSION['success_display'] = 'flex';
                    } else {
                        throw new Exception('Failed to insert audit log entry.');
                    }
                } else {
                    throw new Exception('Neither librarian nor admin ID is available.');
                }
            } else {
                $_SESSION['error_message'] = 'Failed to update patron status.';
                $_SESSION['error_display'] = 'flex';
            }
        } else {
            // Prepare the SQL statement for updating the patron's information
            $sql = "UPDATE patrons SET 
                            application_status = :application_status, 
                            application_status_reason = :application_status_reason
                    WHERE patrons_id = :patrons_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':application_status', $status);
            $stmt->bindParam(':application_status_reason', $reason);
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Generate card_id and other related information after the patron's information has been updated
                $year = date('Y'); // Current year
                $today = date('m/d/Y'); // Today's date
                $card_id = 'MCL-' . $year . '-' . $patronId; // Generate card_id in the desired format
                $valid_until = date('m/d/Y', strtotime('+1 year')); // Valid until date (one year from today)

                // Prepare the SQL statement to insert into patrons_library_id table
                $patronsLibraryIdStmt = $pdo->prepare("
                    INSERT INTO patrons_library_id (card_id, patrons_id, guarantor_id, date_issued, valid_until)
                    VALUES (:card_id, :patrons_id, :guarantor_id, :date_issued, :valid_until)
                ");

                // Bind parameters for the insert statement
                $patronsLibraryIdStmt->bindParam(':card_id', $card_id);
                $patronsLibraryIdStmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);
                $patronsLibraryIdStmt->bindParam(':guarantor_id', $guarantorId, PDO::PARAM_INT);
                $patronsLibraryIdStmt->bindParam(':date_issued', $today);
                $patronsLibraryIdStmt->bindParam(':valid_until', $valid_until);

                // Execute the insert statement
                if ($patronsLibraryIdStmt->execute()) {
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

                        if ($auditStmt->execute()) {
                            $_SESSION['success_message'] = 'Updated and library ID created successfully.';
                            $_SESSION['success_display'] = 'flex';
                        } else {
                            throw new Exception('Failed to insert audit log entry.');
                        }
                    } else {
                        throw new Exception('Neither librarian nor admin ID is available.');
                    }
                } else {
                    $_SESSION['error_message'] = 'Failed to create library ID.';
                    $_SESSION['error_display'] = 'flex';
                }
            } else {
                $_SESSION['error_message'] = 'Failed to update patron status.';
                $_SESSION['error_display'] = 'flex';
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        $_SESSION['error_display'] = 'flex';
    }

    // Redirect to the appropriate page
    header('Location: ../application.php');
    exit();
}
