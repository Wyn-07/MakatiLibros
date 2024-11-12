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
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $company_contact = filter_var($_POST['company_contact'], FILTER_SANITIZE_STRING);
    $company_address = filter_var($_POST['company_address'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);




    $guarantor_firstname = filter_var($_POST['guarantor_firstname'], FILTER_SANITIZE_STRING);
    $guarantor_middlename = filter_var($_POST['guarantor_middlename'], FILTER_SANITIZE_STRING);
    $guarantor_lastname = filter_var($_POST['guarantor_lastname'], FILTER_SANITIZE_STRING);
    $guarantor_suffix = filter_var($_POST['guarantor_suffix'], FILTER_SANITIZE_STRING);
    $guarantor_contact = filter_var($_POST['guarantor_contact'], FILTER_SANITIZE_STRING);
    $guarantor_address = filter_var($_POST['guarantor_address'], FILTER_SANITIZE_STRING);
    $guarantor_company_name = filter_var($_POST['guarantor_company_name'], FILTER_SANITIZE_STRING);
    $guarantor_company_contact = filter_var($_POST['guarantor_company_contact'], FILTER_SANITIZE_STRING);
    $guarantor_company_address = filter_var($_POST['guarantor_company_address'], FILTER_SANITIZE_STRING);

    // Initialize $imageName with default image
    $imageName = 'default_image.png';

    // Process image upload
    if (isset($_FILES['add_image']) && $_FILES['add_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['add_image'];
        $imageTmpName = $image['tmp_name'];
        $currentDateTime = date('Ymd_His');
        $imageName = $lastname . '_' . $currentDateTime . '.jpg';
        $targetDir = '../../patron_images/';
        $targetFilePath = $targetDir . $imageName;

        if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
            $_SESSION['error_message'] = 'Failed to upload image.';
            header('Location: ../patrons.php');
            exit();
        }
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format. Please provide a valid email.';
        header('Location: ../patrons.php');
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
            <h1>Welcome to Our Library, $firstname $lastname!</h1>
            <p>Thank you for registering with us. We are thrilled to have you as a part of our library community. Here are your account details:</p>
            <ul>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Password:</strong> $password</li>
            </ul>
            <p>You can now log in to your account to start exploring our collection and enjoy the many resources we have to offer.</p>
            <p>If you have any questions or need assistance, feel free to reach out to us. Welcome aboard!</p>
            <p>Best regards,<br>The Library Team</p>
        ";

        $mail->send();
        $_SESSION['success_message'] = 'Welcome email sent. Please check your email for account details.';
        $_SESSION['success_display'] = 'flex';
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        header('Location: ../patrons.php');
        exit();
    }

    // Insert into database only if all required fields are provided
    if (!empty($firstname) && !empty($lastname) && !empty($email)) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Insert data into the patrons table
            $stmt = $pdo->prepare("INSERT INTO patrons (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, company_name, company_contact, company_address, email, password, image)
                                   VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :address, :company_name, :company_contact, :company_address, :email, :password, :image)");

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
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':company_contact', $company_contact);
            $stmt->bindParam(':company_address', $company_address);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);  // Ensure this is hashed
            $stmt->bindParam(':image', $imageName);

            if ($stmt->execute()) {

                $patrons_id = $pdo->lastInsertId();

                // Insert data into guarantor table
                $guarantor_stmt = $pdo->prepare("INSERT INTO guarantor (firstname, middlename, lastname, suffix, contact, address, company_name, company_contact, company_address)
                                                 VALUES (:guarantor_firstname, :guarantor_middlename, :guarantor_lastname, :guarantor_suffix, :guarantor_contact, :guarantor_address, :guarantor_company_name, :guarantor_company_contact, :guarantor_company_address)");

                // Bind guarantor parameters
                $guarantor_stmt->bindParam(':guarantor_firstname', $guarantor_firstname);
                $guarantor_stmt->bindParam(':guarantor_middlename', $guarantor_middlename);
                $guarantor_stmt->bindParam(':guarantor_lastname', $guarantor_lastname);
                $guarantor_stmt->bindParam(':guarantor_suffix', $guarantor_suffix);
                $guarantor_stmt->bindParam(':guarantor_contact', $guarantor_contact);
                $guarantor_stmt->bindParam(':guarantor_address', $guarantor_address);
                $guarantor_stmt->bindParam(':guarantor_company_name', $guarantor_company_name);
                $guarantor_stmt->bindParam(':guarantor_company_contact', $guarantor_company_contact);
                $guarantor_stmt->bindParam(':guarantor_company_address', $guarantor_company_address);

                if ($guarantor_stmt->execute()) {
                    // Get the last inserted patron ID and guarantor ID
                    $guarantor_id = $pdo->lastInsertId();

                    $year = date('Y');  // Get the current year (e.g., 2024)
                    $today = date('m/d/Y');  // Get today's date (e.g., 02/13/2024)

                    // Generate the card_id in the desired format: MCL-YYYY-LibraryID
                    $card_id = 'MCL-' . $year . '-' . $patrons_id;

                    // Calculate valid until date (one year later)
                    $valid_until = date('m/d/Y', strtotime('+1 year'));  // Add one year to today's date

                    // Insert into patrons_library_id table
                    $patrons_library_id_stmt = $pdo->prepare("INSERT INTO patrons_library_id (card_id, patrons_id, guarantor_id, date_issued, valid_until)
                       VALUES (:card_id, :patrons_id, :guarantor_id, :date_issued, :valid_until)");

                    // Bind parameters
                    $patrons_library_id_stmt->bindParam(':card_id', $card_id);
                    $patrons_library_id_stmt->bindParam(':patrons_id', $patrons_id);
                    $patrons_library_id_stmt->bindParam(':guarantor_id', $guarantor_id);
                    $patrons_library_id_stmt->bindParam(':date_issued', $today);
                    $patrons_library_id_stmt->bindParam(':valid_until', $valid_until);



                    if ($patrons_library_id_stmt->execute()) {
                        $_SESSION['success_message'] = 'Patron and guarantor information added successfully, and library card issued.';
                    } else {
                        throw new Exception('Failed to add library card information.');
                    }

                    // Commit transaction
                    $pdo->commit();
                } else {
                    throw new Exception('Failed to add guarantor information.');
                }
            } else {
                throw new Exception('Failed to add patron information.');
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error_message'] = 'Failed to add patron, guarantor, and/or library card information. Error: ' . $e->getMessage();
        }

        $_SESSION['success_display'] = 'flex';
        header('Location: ../patrons.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'First name, last name, and email cannot be empty.';
        header('Location: ../patrons.php');
        exit();
    }
}
