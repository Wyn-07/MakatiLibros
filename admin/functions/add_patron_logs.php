<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $log_date = filter_var($_POST['log_date'], FILTER_SANITIZE_STRING);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['suffix'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $barangay = filter_var($_POST['barangay'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $purpose = filter_var($_POST['purpose'], FILTER_SANITIZE_STRING);
    $sector = filter_var($_POST['sector'], FILTER_SANITIZE_STRING);
    $sector_details = filter_var($_POST['sector_details'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($log_date) && !empty($firstname) && !empty($lastname) && !empty($purpose)) {
        try {
            // Prepare the SQL statement for inserting a new patron log
            $stmt = $pdo->prepare("INSERT INTO patron_logs (log_date, firstname, middlename, lastname, suffix, age, gender, barangay, city, purpose, sector, sector_details)
                                   VALUES (:log_date, :firstname, :middlename, :lastname, :suffix, :age, :gender, :barangay, :city, :purpose, :sector, :sector_details)");

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
            $stmt->bindParam(':purpose', $purpose);
            $stmt->bindParam(':sector', $sector);
            $stmt->bindParam(':sector_details', $sector_details);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Patron log added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add patron log.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add patron log. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../patron_logs.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Log date, first name, last name, and purpose cannot be empty.';
        header('Location: ../patron_logs.php');
        exit();
    }
}
?>
