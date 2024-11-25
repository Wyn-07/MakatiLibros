<?php
session_start();
date_default_timezone_set('Asia/Manila');  

include '../../connection.php';

if (isset($_POST['submit'])) {
    $librarianId = filter_var($_POST['librarian_id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['profile_firstname'], FILTER_SANITIZE_STRING);
    $middlename = filter_var($_POST['profile_middlename'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['profile_lastname'], FILTER_SANITIZE_STRING);
    $suffix = filter_var($_POST['profile_suffix'], FILTER_SANITIZE_STRING);
    $birthdate = filter_var($_POST['profile_birthdate'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['profile_age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['profile_gender'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['profile_contact'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['profile_address'], FILTER_SANITIZE_STRING);

    // Convert the birthdate format from YYYY-MM-DD to MM/DD/YYYY
    $date = DateTime::createFromFormat('Y-m-d', $birthdate);
    $formattedBirthdate = $date->format('m/d/Y');

    // Initialize image variable
    $imageName = null;

    // Process the image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['profile_image'];
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
            header('Location: ../dashboard.php');
            exit();
        }
    }

    if (!empty($librarianId) && !empty($firstname) && !empty($lastname)) {
        try {
            // Prepare the SQL update statement
            $sql = "UPDATE librarians SET 
                        firstname = :firstname, 
                        middlename = :middlename, 
                        lastname = :lastname, 
                        suffix = :suffix, 
                        birthdate = :birthdate, 
                        age = :age, 
                        gender = :gender, 
                        contact = :contact, 
                        address = :address" . 
                        (!empty($imageName) ? ", image = :image" : "") . 
                    " WHERE librarians_id = :librarian_id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':birthdate', $formattedBirthdate); 
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':librarian_id', $librarianId, PDO::PARAM_INT);
            
            // Bind the image parameter only if a new image was uploaded
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }

            // Execute the update
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Librarian information updated successfully.';
                $_SESSION['success_display'] = 'flex';
            } else {
                $_SESSION['error_message'] = 'Failed to update librarian information.';
                $_SESSION['error_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update librarian information. Error: ' . $e->getMessage();
        }

        header('Location: ../dashboard.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Librarian ID, first name, and last name cannot be empty.';
        header('Location: ../dashboard.php');
        exit();
    }
}
?>
