<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $log_id = filter_var($_POST['log_id'], FILTER_SANITIZE_NUMBER_INT); // log_id to identify the record
    $log_date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $barangay = filter_var($_POST['barangay'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $book_title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($log_id) && !empty($log_date) && !empty($name) && !empty($book_title)) {
        try {
            // Prepare the SQL statement for updating the borrow log
            $stmt = $pdo->prepare("UPDATE borrow_logs 
                                   SET log_date = :log_date, name = :name, age = :age, gender = :gender, 
                                       barangay = :barangay, city = :city, category = :category, book_title = :book_title
                                   WHERE log_id = :log_id");

            // Bind parameters
            $stmt->bindParam(':log_date', $log_date);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':book_title', $book_title);
            $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT); // Bind the log_id to the query

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
        $_SESSION['error_message'] = 'Log ID, log date, name, and book title cannot be empty.';
        header('Location: ../borrow_logs.php');
        exit();
    }
}
?>
