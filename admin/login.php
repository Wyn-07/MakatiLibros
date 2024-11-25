<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <title>Login</title>

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
</head>

<?php
include '../connection.php';

session_start();

if (isset($_POST['login'])) {
    $emailadd = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the PDO query to check in the librarians table
    $query = "SELECT * FROM librarians WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $emailadd);
    $stmt->execute();
    $librarian = $stmt->fetch(PDO::FETCH_ASSOC);

    // If not found in librarians table, check the admin table
    if (!$librarian) {
        $query = "SELECT * FROM admin WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $emailadd);
        $stmt->execute();
        $librarian = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($librarian) {
        $storedPassword = $librarian['password'];

        // Check if the stored password is hashed or plain
        if (password_verify($password, $storedPassword) || $storedPassword === $password) {
            // Password matches
            $_SESSION['success_message'] = "Logged In Successfully.";
            $_SESSION['success_display'] = "flex"; // Display the success message
            $_SESSION['email'] = $emailadd;
            $_SESSION['librarians_id'] = isset($librarian['librarians_id']) ? $librarian['librarians_id'] : $librarian['admin_id']; // Use appropriate ID field
            $_SESSION['role'] = isset($librarian['librarians_id']) ? 'librarian' : 'admin'; // Add role to differentiate between librarians and admins
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error_message'] = "Invalid Password";
            $_SESSION['error_display'] = "flex"; // Display the error message
        }
    } else {
        // Email not found
        $_SESSION['error_message'] = "Invalid Email";
        $_SESSION['error_display'] = "flex"; // Display the error message
    }
}



?>








<body>
    <div class="wrapper">
        <div class="container-body-login">
            <div class="transparent">
                <div class="container-white-login">

                    <div class="row-center">
                        <div class="container-round login">
                            <img src="../images/library-logo.png" alt="" class="image">
                        </div>
                        <div>
                            <div class="title-26px">
                                Login
                            </div>
                            <div class="font-size-16">
                                MakatiLibros 
                            </div>
                        </div>
                    </div>


                    <form action="" method="POST">
                        <div class="container-form">

                            <div class="container-input">
                                <div class="container-success" id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                                                        unset($_SESSION['success_display']); ?>;">
                                    <div class="container-success-description">
                                        <?php if (isset($_SESSION['success_message'])) {
                                            echo $_SESSION['success_message'];
                                            unset($_SESSION['success_message']);
                                        } ?>
                                    </div>
                                    <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                                </div>


                                <div class="container-error" id="container-error" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                    unset($_SESSION['error_display']); ?>;">
                                    <div class="container-error-description">
                                        <?php if (isset($_SESSION['error_message'])) {
                                            echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']);
                                        } ?>
                                    </div>
                                    <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                                </div>

                                <div class="container-input-100">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-100">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" class="input-text" autocomplete="off" required>
                                </div>
                            </div>



                            <div class="row row-right">
                                <button type="submit" name="login" class="button-submit">Login</button>
                            </div>
                        </div>
                    </form>

                    <div class="row-center">
                        <!-- <a href="register.php" class="login-link font-14px">Register</a> -->
                        <a href="reset.php" class="login-link  font-14px ">Reset Password</a>
                    </div>


                </div>
            </div>
        </div>

    </div>
</body>

</html>

<script src="js/close-status.js"></script>