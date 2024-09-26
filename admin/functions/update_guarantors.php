<?php
session_start();
include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $guarantorId = filter_var($_POST['guarantor_id'], FILTER_SANITIZE_NUMBER_INT);
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
    if (!empty($guarantorId) && !empty($firstname) && !empty($lastname) && !empty($contact)) {
        try {
            // Prepare the SQL statement for updating the guarantor's information
            $stmt = $pdo->prepare("UPDATE guarantor SET firstname = :firstname, middlename = :middlename, lastname = :lastname, suffix = :suffix, contact = :contact, address = :address, company_name = :company_name, company_contact = :company_contact, company_address = :company_address WHERE guarantor_id = :guarantor_id");

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
            $stmt->bindParam(':guarantor_id', $guarantorId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Guarantor information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update guarantor information.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update guarantor information. Error: ' . $e->getMessage();
        }

        // Redirect to the appropriate page
        header('Location: ../guarantors.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Guarantor ID, first name, last name, and contact cannot be empty.';
        header('Location: ../guarantors.php');
        exit();
    }
}
?>
