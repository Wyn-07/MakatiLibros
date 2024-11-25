<?php
session_start();
date_default_timezone_set('Asia/Manila');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../../connection.php';
require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate input data
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
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);


    // Initialize $imageName with default image
    $imageName = 'default_image.jfif';

    // Process image upload
    if (isset($_FILES['add_image']) && $_FILES['add_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['add_image'];
        $imageTmpName = $image['tmp_name'];
        $currentDateTime = date('Ymd_His');
        $imageName = $lastname . '_' . $currentDateTime . '.jpg';
        $targetDir = '../../librarian_images/';
        $targetFilePath = $targetDir . $imageName;

        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../librarian.php');
            exit();
        }
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format. Please provide a valid email.';
        header('Location: ../librarian.php');
        exit();
    }

    // Send welcome email using PHPMailer
    try {
        // Check if email already exists
        $emailCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM librarians WHERE email = :email");
        $emailCheckStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $emailCheckStmt->execute();
        $emailExists = $emailCheckStmt->fetchColumn();

        if ($emailExists > 0) {
            $_SESSION['error_message'] = 'Email is already registered. Please use a different email.';
            $_SESSION['error_display'] = 'flex';
            header('Location: ../librarian.php');
            exit();
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'khuftheprogrammer@gmail.com';
        $mail->Password = 'siqyswepryjsqxyv';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('khuftheprogrammer@gmail.com', 'Makati City Hall Library');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Our Library!';
        $mail->Body = "
            <h1>Welcome to the Library Management System, $firstname $lastname!</h1>
            <p>Your librarian account has been successfully created. You can now access the library management system to assist patrons and oversee resources. Here are your account details:</p>
            <ul>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Password:</strong> $password</li>
            </ul>
            <p>Use these credentials to log in and begin managing library operations.</p>
            <p>If you have any questions or need assistance, please feel free to reach out to me directly. Welcome aboard, and I look forward to working with you!</p>
            <p>Best regards,<br>Andrian Cuerdo<br>Library Administrator</p>
        ";


        $mail->send();
        $_SESSION['success_message'] = 'Welcome email sent. Please check your email for account details.';
        $_SESSION['success_display'] = 'flex';
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        header('Location: ../librarian.php');
        exit();
    }

    // Insert into database only if all required fields are provided
    if (!empty($firstname) && !empty($lastname) && !empty($email)) {
        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Insert data into the librarian table
            $stmt = $pdo->prepare("INSERT INTO librarians (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, email, password, image)
                                   VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :address, :email, :password, :image)");

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
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':image', $imageName);

            // Execute the query
            $stmt->execute();

            // Commit the transaction
            $pdo->commit();


            $oldData = "<p class='italic-data'>No old data is present because new data is being added.</p>";
            $newData = "<div class='container-form'>
                            <div class='container-input'>
                                <div class='container-form-patron'>
                                    <div class='form-patron'>
                                        <img src='../librarian_images/$imageName' class='image'>
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

            $librarianId = isset($_SESSION['librarian_id']) ? $_SESSION['librarian_id'] : null;
            $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
            $page = "Librarian Page";
            $description_audit = "Added a new librarian";

            $currentAuditDate = date('Y-m-d H:i:s'); // e.g., 2024-09-19 23:58:32


            // Prepare the audit log insertion
            $auditSql = "";
            $auditStmt = null;

            if ($librarianId) {
                $auditSql = "
                        INSERT INTO librarian_audit (
                            date_time, old_data, new_data, librarians_id, page, description
                        ) VALUES (
                            :date_time, :old_data, :new_data, :librarians_id, :page, :description
                        )";
                $auditStmt = $pdo->prepare($auditSql);
                $auditStmt->bindParam(':librarians_id', $librarianId, PDO::PARAM_INT);
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



            // Set success message
            $_SESSION['success_message'] = 'Librarian added successfully.';
            $_SESSION['success_display'] = 'flex';

            // Redirect to librarian page
            header('Location: ../librarian.php');
            exit();
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $pdo->rollBack();

            // Set error message
            $_SESSION['error_message'] = 'Failed to add librarian information. Error: ' . $e->getMessage();
            $_SESSION['error_display'] = 'flex';

            // Redirect back to librarian page
            header('Location: ../librarian.php');
            exit();
        }
    } else {
        // If any required field is empty
        $_SESSION['error_message'] = 'First name, last name, and email cannot be empty.';
        $_SESSION['error_display'] = 'flex';

        // Redirect back to the librarian page
        header('Location: ../librarian.php');
        exit();
    }
}
