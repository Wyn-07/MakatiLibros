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


    $oldFirstname = filter_var($_POST['oldFirstname'], FILTER_SANITIZE_STRING);
    $oldMiddlename = filter_var($_POST['oldMiddlename'], FILTER_SANITIZE_STRING);
    $oldLastname = filter_var($_POST['oldLastname'], FILTER_SANITIZE_STRING);
    $oldSuffix = filter_var($_POST['oldSuffix'], FILTER_SANITIZE_STRING);
    $oldBirthdate = filter_var($_POST['oldBirthdate'], FILTER_SANITIZE_STRING);
    $oldAge = filter_var($_POST['oldAge'], FILTER_SANITIZE_NUMBER_INT);
    $oldGender = filter_var($_POST['oldGender'], FILTER_SANITIZE_STRING);
    $oldContact = filter_var($_POST['oldContact'], FILTER_SANITIZE_STRING);
    $oldAddress = filter_var($_POST['oldAddress'], FILTER_SANITIZE_STRING);
    $oldEmail = filter_var($_POST['oldEmail'], FILTER_SANITIZE_EMAIL);
    $oldImageName = filter_var($_POST['oldImageName'], FILTER_SANITIZE_EMAIL);


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

                    $newImageName = isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK
                    ? $imageName 
                    : $oldImageName;

                    if ($oldFirstname === $firstname && $oldMiddlename === $middlename && $oldLastname === $lastname && 
                            $oldSuffix === $suffix && $oldBirthdate === $birthdate && $oldAge=== $age && $oldGender === $gender && 
                            $oldContact === $contact && $oldAddress === $address && $oldEmail === $email && $imageName === null) {
                       
                        $_SESSION['success_message'] = 'No changes detected.';
                        $_SESSION['success_display'] = 'flex';

                        // Redirect to the appropriate page
                        header('Location: ../librarian.php');
                        exit();
                        
                    }

                    $oldData = "<div class='container-form'>
                                    <div class='container-input'>
                                        <div class='container-form-patron'>
                                            <div class='form-patron'>
                                                <img src='../librarian_images/$oldImageName' class='image'>
                                            </div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>First Name:</label>
                                            <div class='input-text'>$oldFirstname</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Middle Name</label>
                                            <div class='input-text'>$oldMiddlename</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Last Name:</label>
                                            <div class='input-text'>$oldLastname</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Suffix</label>
                                            <div class='input-text'>$oldSuffix</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Birthdate:</label>
                                            <div class='input-text'>$oldBirthdate</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Age:</label>
                                            <div class='input-text'>$oldAge</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Gender:</label>
                                            <div class='input-text'>$oldGender</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Contact:</label>
                                            <div class='input-text'>$oldContact</div>
                                        </div>
                                        <div class='container-input-100'>
                                            <label>Address:</label>
                                            <div class='input-text'>$oldAddress</div>
                                        </div>
                                        <div class='container-input-100'>
                                            <label>Email:</label>
                                            <div class='input-text'>$oldEmail</div>
                                        </div>
                                    </div>
                                </div>";
                    $newData = "<div class='container-form'>
                                    <div class='container-input'>
                                        <div class='container-form-patron'>
                                            <div class='form-patron'>
                                                <img src='../librarian_images/$newImageName' class='image'>
                                            </div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>First Name:</label>
                                            <div class='input-text'>$firstname</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Middle Name</label>
                                            <div class='input-text'>$middlename</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Last Name:</label>
                                            <div class='input-text'>$lastname</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Suffix</label>
                                            <div class='input-text'>$suffix</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Birthdate:</label>
                                            <div class='input-text'>$birthdate</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Age:</label>
                                            <div class='input-text'>$age</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Gender:</label>
                                            <div class='input-text'>$gender</div>
                                        </div>
                                        <div class='container-input-49'>
                                            <label>Contact:</label>
                                            <div class='input-text'>$contact</div>
                                        </div>
                                        <div class='container-input-100'>
                                            <label>Address:</label>
                                            <div class='input-text'>$address</div>
                                        </div>
                                        <div class='container-input-100'>
                                            <label>Email:</label>
                                            <div class='input-text'>$email</div>
                                        </div>
                                    </div>
                                </div>";

                    $librarianID = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
                    $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
                    $page = "Librarian Page";
                    $description_audit = "Updated information of librarian ID " . $librarianId;

                    $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


                    // Prepare the audit log insertion
                    $auditSql = "";
                    $auditStmt = null;

                    if ($librarianID) {
                        $auditSql = "
                        INSERT INTO librarian_audit (
                            date_time, old_data, new_data, librarians_id, page, description
                        ) VALUES (
                            :date_time, :old_data, :new_data, :librarians_id, :page, :description
                        )";
                        $auditStmt = $pdo->prepare($auditSql);
                        $auditStmt->bindParam(':librarians_id', $librarianID, PDO::PARAM_INT);
                    } elseif ($adminId) {
                        $auditSql = "
                        INSERT INTO admin_audit (
                            date_time, old_data, new_data, admin_id, page, description
                        ) VALUES (
                            :date_time, :old_data, :new_data, :admin_id, :page, :description
                        )";
                        $auditStmt = $pdo->prepare($auditSql);
                        $auditStmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
                    }

                    if ($auditStmt) {
                        $auditStmt->bindParam(':date_time', $currentAuditDate, PDO::PARAM_STR);
                        $auditStmt->bindParam(':old_data', $oldData, PDO::PARAM_STR);
                        $auditStmt->bindParam(':new_data', $newData, PDO::PARAM_STR);
                        $auditStmt->bindParam(':page', $page, PDO::PARAM_STR);
                        $auditStmt->bindParam(':description', $description_audit, PDO::PARAM_STR);

                        if (!$auditStmt->execute()) {
                            throw new Exception('Failed to insert audit log entry.');
                        }
                    } else {
                        throw new Exception('Neither librarian nor admin ID is available.');
                    }


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
