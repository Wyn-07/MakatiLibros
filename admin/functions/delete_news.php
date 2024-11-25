<?php
session_start();

date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['news_id'])) {
    // Sanitize the news_id
    $newsId = filter_var($_POST['news_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($newsId)) {
        try {
            // Fetch the news details
            $fetchSql = "SELECT title, date, description, image FROM news WHERE news_id = :news_id";
            $fetchStmt = $pdo->prepare($fetchSql);
            $fetchStmt->bindParam(':news_id', $newsId, PDO::PARAM_INT);
            $fetchStmt->execute();

            $news = $fetchStmt->fetch(PDO::FETCH_ASSOC);

            if (!$news) {
                throw new Exception('News not found.');
            }

            $title = $news['title'];
            $date = $news['date'];
            $description = $news['description'];
            $image = $news['image'];

            // Prepare SQL delete query
            $sql = "DELETE FROM news WHERE news_id = :news_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':news_id', $newsId, PDO::PARAM_INT);

            // Execute the delete query
            if ($stmt->execute()) {
                $oldData = "<div class='container-form-official'>
                                <div class='container-news-image-modal'>
                                    <img src='../news_images/$image' class='image'>
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
                $newData = "<p class='italic-data'>No new data is present because old data is being deleted.</p>";

                $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                $page = "News Page";
                $descriptionAudit = "Deleted News ID " . $newsId;

                $currentAuditDate = date('Y-m-d H:i:s');

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
                    $auditStmt->bindParam(':description', $descriptionAudit, PDO::PARAM_STR);

                    if (!$auditStmt->execute()) {
                        throw new Exception('Failed to insert audit log entry.');
                    }
                } else {
                    throw new Exception('Neither librarian nor admin ID is available.');
                }

                $_SESSION['success_message'] = 'News deleted successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to delete news.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect back to the previous page
        header('Location: ../news.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid News ID.';
        $_SESSION['error_display'] = 'flex';
        header('Location: ../news.php');
        exit();
    }
}
?>
