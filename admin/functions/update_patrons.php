<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $patronId = filter_var($_POST['patrons_id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['suffix'], FILTER_SANITIZE_STRING);
    $birthdate = filter_var($_POST['birthdate'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $interest = filter_var($_POST['interest'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING); // Assuming the password is not hashed here

    // Check if required fields are empty
    if (!empty($patronId) && !empty($firstname) && !empty($lastname) && !empty($email)) {
        try {
            // Prepare the SQL statement for updating the patron's information
            $stmt = $pdo->prepare("UPDATE patrons SET firstname = :firstname, middlename = :middlename, lastname = :lastname, suffix = :suffix, birthdate = :birthdate, age = :age, gender = :gender, contact = :contact, address = :address, interest = :interest, email = :email, password = :password WHERE patrons_id = :patrons_id");

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
            $stmt->bindParam(':interest', $interest);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // It's advisable to hash passwords
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Patron information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update patron information.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update patron information. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../patrons.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Patron ID, first name, last name, and email cannot be empty.';
        header('Location: ../patrons.php');
        exit();
    }
}
?>
