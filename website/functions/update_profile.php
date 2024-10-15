<?php
session_start();
date_default_timezone_set('Asia/Manila');  

include '../../connection.php';

if (isset($_POST['submit'])) {
    $patronId = filter_var($_POST['patron_id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['suffix'], FILTER_SANITIZE_STRING);
    $birthdate = filter_var($_POST['birthdate'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $company_contact = filter_var($_POST['company_contact'], FILTER_SANITIZE_STRING);
    $company_address = filter_var($_POST['company_address'], FILTER_SANITIZE_STRING);


    // Initialize image variable
    $imageName = null;

    // Process the image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['profile_image'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "patronid_lastname_date_time"
        $imageName = $patronId . '_' . $lastname . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../patron_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../profile.php');
            exit();
        }
    }

    if (!empty($patronId) && !empty($firstname) && !empty($lastname)) {
        try {
            // Prepare the SQL update statement
            $sql = "UPDATE patrons SET 
                        firstname = :firstname, 
                        middlename = :middlename, 
                        lastname = :lastname, 
                        suffix = :suffix, 
                        birthdate = :birthdate, 
                        age = :age, 
                        gender = :gender, 
                        contact = :contact, 
                        address = :address,
                        company_name = :company_name,
                        company_contact = :company_contact,
                        company_address = :company_address" . 
                        (!empty($imageName) ? ", image = :image" : "") . 
                    " WHERE patrons_id = :patrons_id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':birthdate', $birthdate); 
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':company_contact', $company_contact);
            $stmt->bindParam(':company_address', $company_address);
            $stmt->bindParam(':patrons_id', $patronId, PDO::PARAM_INT);
            
            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the update
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Patron information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update patron information.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update patron information. Error: ' . $e->getMessage();
        }

        header('Location: ../profile.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Patron ID, first name, and last name cannot be empty.';
        header('Location: ../profile.php');
        exit();
    }
}
?>
