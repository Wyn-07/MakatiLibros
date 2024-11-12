<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $librarianId = filter_var($_POST['librarians_id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['edit_firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['edit_middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['edit_lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['edit_suffix'], FILTER_SANITIZE_STRING);
    $birthdate = filter_var($_POST['edit_birthdate'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['edit_age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['edit_gender'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['edit_contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['edit_address'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['edit_email'], FILTER_SANITIZE_EMAIL);

    // Initialize image variable
    $imageName = null;

    // Process the image
    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['edit_image'];
        $imageTmpName = $image['tmp_name'];

        // Extract current date and time
        $currentDateTime = date('Ymd_His');

        // Define the image name format: "librarianid_lastname_date_time"
        $imageName = $librarianId . '_' . $lastname . '_' . $currentDateTime . '.jpg';

        // Set the target directory and file path
        $targetDir = '../../librarian_images/';
        $targetFilePath = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../librarian.php');
            exit();
        }
    }

    // Check if required fields are empty
    if (!empty($librarianId) && !empty($firstname) && !empty($lastname) && !empty($email)) {
        try {
            // Prepare the SQL statement for updating the librarian's information
            $sql = "UPDATE librarians SET 
                        firstname = :firstname, 
                        middlename = :middlename, 
                        lastname = :lastname, 
                        suffix = :suffix, 
                        birthdate = :birthdate, 
                        age = :age, 
                        gender = :gender, 
                        contact = :contact, 
                        address = :address, 
                        email = :email" . 
                (!empty($imageName) ? ", image = :image" : "") . 
                " WHERE librarians_id = :librarians_id";

            $stmt = $pdo->prepare($sql);

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
            $stmt->bindParam(':librarians_id', $librarianId, PDO::PARAM_INT);

            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the statement and check if any rows were updated
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['success_message'] = 'Librarian information updated successfully.';
                    $_SESSION['success_display'] = 'flex';
                } else {
                    $_SESSION['error_message'] = 'No changes were made to the librarian information.';
                    $_SESSION['error_display'] = 'flex';
                }
            } else {
                $_SESSION['error_message'] = 'Failed to execute the update statement.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update librarian information. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';
        }

        // Redirect to the appropriate page
        header('Location: ../librarian.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Librarian ID, first name, last name, and email cannot be empty.';
        header('Location: ../librarian.php');
        exit();
    }
}
