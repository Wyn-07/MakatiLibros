<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $log_date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $barangay = filter_var($_POST['barangay'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $book_title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($log_date) && !empty($name) && !empty($book_title)) {
        try {
            // Prepare the SQL statement for inserting a new borrow log
            $stmt = $pdo->prepare("INSERT INTO borrow_logs (log_date, name, age, gender, barangay, city, category, book_title)
                                   VALUES (:log_date, :name, :age, :gender, :barangay, :city, :category, :book_title)");

            // Bind parameters
            $stmt->bindParam(':log_date', $log_date);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':book_title', $book_title);

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
        $_SESSION['error_message'] = 'Log date, first name, and book title cannot be empty.';
        header('Location: ../borrow_logs.php');
        exit();
    }
}
?>
