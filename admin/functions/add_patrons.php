<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['suffix'], FILTER_SANITIZE_STRING);
    $birthdate = filter_var($_POST['birthdate'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $interest = filter_var($_POST['interest'], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if required fields are empty
    if (!empty($firstname) && !empty($lastname) && !empty($email)) {
        try {
            // Prepare the SQL statement for inserting a new patron's information
            $stmt = $pdo->prepare("INSERT INTO patrons (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, email, interest, password)
                                   VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :address, :email, :interest, :password)");

            // Bind parameters
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':interest', $interest);
            $stmt->bindParam(':password', $password);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Patron information added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add patron information.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add patron information. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../patrons.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'First name, last name, and email cannot be empty.';
        header('Location: ../patrons.php');
        exit();
    }
}
?>
