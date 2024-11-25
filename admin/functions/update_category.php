<?php
session_start();

date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    $categoryId = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $categoryName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $categoryDescription = filter_var($_POST['edit_categ_description'], FILTER_SANITIZE_STRING);


    $categoryOldName = filter_var($_POST['oldName'], FILTER_SANITIZE_STRING);
    $categoryOldDescription = filter_var($_POST['old_edit_categ_description'], FILTER_SANITIZE_STRING);


    if (!empty($categoryId) && !empty($categoryName)) {
        try {
            // Prepare the SQL statement for updating the category
            $stmt = $pdo->prepare("UPDATE category SET category = :category, description = :description WHERE category_id = :category_id");

            // Bind parameters
            $stmt->bindParam(':category', $categoryName);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':description', $categoryDescription);


            // Execute the statement
            if ($stmt->execute()) {

                $newImageName = isset($_FILES['official_image']) && $_FILES['official_image']['error'] === UPLOAD_ERR_OK
                    ? $imageName
                    : $oldImageName;


                if ($categoryOldName === $categoryName && $categoryOldDescription === $categoryDescription) {
                    $_SESSION['success_message'] = 'No changes detected.';
                    $_SESSION['success_display'] = 'flex';
                    // Redirect to the appropriate page
                    header('Location: ../category.php');
                    exit();
                }

                $oldData = $categoryOldName . " <br> " . $categoryOldDescription;

                $newData = $categoryName . " <br> " . $categoryDescription;


                $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                $page = "Books Page: Category";
                $description_audit = "Updated information of Category ID " . $categoryId;

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


                $_SESSION['success_message'] = 'Updated successfully';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['success_message'] = 'Failed to update category';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update category. Error: ' . $e->getMessage();
        }

        header('Location: ../category.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'category ID or name cannot be empty.';
        header('Location: ../category.php');
        exit();
    }
}
