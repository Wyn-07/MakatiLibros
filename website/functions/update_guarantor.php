<?php
session_start();
date_default_timezone_set('Asia/Manila');

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Fetch and sanitize input fields
    $patronId = filter_var($_POST['patron_id'], FILTER_SANITIZE_NUMBER_INT);
    $guarantorId = filter_var($_POST['guarantor_id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['guarantor_firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['guarantor_middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['guarantor_lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['guarantor_suffix'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['guarantor_contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['guarantor_address'], FILTER_SANITIZE_STRING);
    $companyName = filter_var($_POST['guarantor_company_name'], FILTER_SANITIZE_STRING);
    $companyContact = filter_var($_POST['guarantor_company_contact'], FILTER_SANITIZE_STRING);
    $companyAddress = filter_var($_POST['guarantor_company_address'], FILTER_SANITIZE_STRING);


    $signName = null;

    // Process the image
    if (isset($_FILES['guarantor_sign']) && $_FILES['guarantor_sign']['error'] === UPLOAD_ERR_OK) {
        $sign = $_FILES['guarantor_sign'];
        $imageTmpName = $sign['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "patronid_lastname_date_time"
        $signName = $guarantorId . '_' . $lastname . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../sign_images/';
        $targetFilePath = $targetDir . $signName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../guarantor.php');
            exit();
        }
    }



    // Ensure mandatory fields are not empty
    if (!empty($guarantorId) && !empty($firstname) && !empty($lastname)) {
        try {
            // Prepare the SQL update statement for the guarantor
            $sql = "UPDATE guarantor SET 
                        firstname = :firstname, 
                        middlename = :middlename, 
                        lastname = :lastname, 
                        suffix = :suffix, 
                        contact = :contact, 
                        address = :address, 
                        company_name = :company_name, 
                        company_contact = :company_contact, 
                        company_address = :company_address" .
                    (!empty($signName) ? ", sign = :sign" : "") .
                    " WHERE guarantor_id = :guarantor_id AND patrons_id = :patrons_id";

            $stmt = $pdo->prepare($sql);

            // Bind parameters to values
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':company_name', $companyName);
            $stmt->bindParam(':company_contact', $companyContact);
            $stmt->bindParam(':company_address', $companyAddress);
            $stmt->bindParam(':guarantor_id', $guarantorId, PDO::PARAM_INT);
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);

            if (!empty($sign)) {
                $stmt->bindParam(':sign', $signName);
            }

            // Execute the update
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Guarantor information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update guarantor information.';
                $_SESSION['error_display'] = 'flex';
            }

        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Error updating guarantor information: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }
    } else {
        $_SESSION['error_message'] = 'Guarantor ID, first name, and last name cannot be empty.';
        $_SESSION['error_display'] = 'flex';
    }

    // Redirect back to profile page
    header('Location: ../guarantor.php');
    exit();
}

?>
