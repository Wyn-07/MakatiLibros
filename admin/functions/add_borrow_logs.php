<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $log_date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $firstname = filter_var($_POST['addFirstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['addMiddlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['addLastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['addSuffix'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['addAge'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['addGender'], FILTER_SANITIZE_STRING);
    $barangay = filter_var($_POST['addBarangay'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['addCity'], FILTER_SANITIZE_STRING);
    $category_id = filter_var($_POST['addCategoryId'], FILTER_SANITIZE_STRING);
    $book_id = filter_var($_POST['addTitleId'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($log_date) && !empty($firstname) && !empty($book_id) && !empty($category_id)) {
        try {
            // Prepare the SQL statement for inserting a new borrow log
            $stmt = $pdo->prepare("INSERT INTO borrow_logs (log_date, firstname, middlename, lastname, suffix, age, gender, barangay, city, category_id, book_id)
                                   VALUES (:log_date, :firstname, :middlename, :lastname, :suffix, :age, :gender, :barangay, :city, :category_id, :book_id)");

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

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Borrow log added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add borrow log.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add borrow log. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../borrow_logs.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Log date, first name, category ID, and book title ID cannot be empty.';
        header('Location: ../borrow_logs.php');
        exit();
    }
}
?>
