<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $log_id = filter_var($_POST['log_id'], FILTER_SANITIZE_NUMBER_INT);
    $log_date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $firstname = filter_var($_POST['editFirstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['editMiddlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['editLastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['editSuffix'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['editAge'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['editGender'], FILTER_SANITIZE_STRING);
    $barangay = filter_var($_POST['editBarangay'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['editCity'], FILTER_SANITIZE_STRING);
    $category_id = filter_var($_POST['editBorrowLogCategoryId'], FILTER_SANITIZE_STRING);
    $book_id = filter_var($_POST['editBookTitleId'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($log_id) && !empty($log_date) && !empty($firstname) && !empty($book_id) && !empty($category_id)) {
        try {
            // Prepare the SQL statement for updating an existing borrow log
            $stmt = $pdo->prepare("UPDATE borrow_logs 
                                   SET log_date = :log_date,
                                       firstname = :firstname,
                                       middlename = :middlename,
                                       lastname = :lastname,
                                       suffix = :suffix,
                                       age = :age,
                                       gender = :gender,
                                       barangay = :barangay,
                                       city = :city,
                                       category_id = :category_id,
                                       book_id = :book_id
                                   WHERE log_id = :log_id");

            // Bind parameters
            $stmt->bindParam(':log_date', $log_date);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':book_id', $book_id);
            $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT); // Bind log_id for the update condition

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Borrow log updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update borrow log.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update borrow log. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../borrow_logs.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Log ID, log date, first name, category ID, and book title ID cannot be empty.';
        header('Location: ../borrow_logs.php');
        exit();
    }
}
?>
