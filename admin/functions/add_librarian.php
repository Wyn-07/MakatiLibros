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
