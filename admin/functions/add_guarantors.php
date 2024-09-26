<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['suffix'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $company_contact = filter_var($_POST['company_contact'], FILTER_SANITIZE_STRING);
    $company_address = filter_var($_POST['company_address'], FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (!empty($firstname) && !empty($lastname) && !empty($contact)) {
        try {
            // Prepare the SQL statement for inserting a new guarantor's information
            $stmt = $pdo->prepare("INSERT INTO guarantor (firstname, middlename, lastname, suffix, contact, address, company_name, company_contact, company_address)
                                   VALUES (:firstname, :middlename, :lastname, :suffix, :contact, :address, :company_name, :company_contact, :company_address)");

            // Bind parameters
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':company_contact', $company_contact);
            $stmt->bindParam(':company_address', $company_address);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Guarantor information added successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to add guarantor information.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add guarantor information. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../guarantors.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'First name, last name, and contact cannot be empty.';
        header('Location: ../guarantors.php');
        exit();
    }
}
?>
