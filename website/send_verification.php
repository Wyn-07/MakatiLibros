<?php
session_start();

// Initialize the response array
$response = array();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Include the PDO database connection
require '../connection.php'; // Update with your actual path to the PDO connection file

// Check if email and verification code are set in the POST request
if (isset($_POST['email']) && isset($_POST['verificationCode'])) {

    // Sanitize input
    $email = $_POST['email'];
    $verificationCode = $_POST['verificationCode'];

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM patrons WHERE email = :email";
    $stmt = $pdo->prepare($checkEmailQuery);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Email already exists, prompt the user to reset their password
        $response['status'] = 'error';
        $response['message'] = 'Email already in use. If you forgot your password, you can use the forgot password below.';
        $response['display'] = 'flex';
        echo json_encode($response);
        exit();
    }

    // Email does not exist, proceed with sending the verification code
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'khuftheprogrammer@gmail.com'; // Your email
        $mail->Password = 'siqyswepryjsqxyv'; // Your app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('khuftheprogrammer@gmail.com', 'Makati City Hall Library');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "
            <h1>Your Verification Code</h1>
            <p>Please use the following code to verify your email:</p>
            <h2>$verificationCode</h2>
            <p>Thank you!</p>
        ";

        $mail->send();

        // Success message
        $_SESSION['success_message'] = "Verification code sent successfully";
        $_SESSION['success_display'] = 'flex'; // Show success message
        $_SESSION['error_display'] = 'none';  // Hide error message

        // Respond with success message
        $response['status'] = 'success';
        $response['message'] = $_SESSION['success_message'];
        $response['display'] = $_SESSION['success_display'];
    } catch (Exception $e) {
        // Error message
        $_SESSION['error_message'] = 'Error: ' . $mail->ErrorInfo;
        $_SESSION['error_display'] = 'flex'; // Show error message
        $_SESSION['success_display'] = 'none'; // Hide success message

        // Respond with error message
        $response['status'] = 'error';
        $response['message'] = $_SESSION['error_message'];
        $response['display'] = $_SESSION['error_display'];
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Email or Verification Code not set.';
    $response['display'] = 'none';
}

echo json_encode($response);
exit();
?>
