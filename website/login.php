<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php
include '../connection.php';

session_start();

if (isset($_POST['login'])) {
    $emailadd = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the PDO query
    $query = "SELECT * FROM patrons WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $emailadd);
    $stmt->execute();
    $patron = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patron) {
        $storedPassword = $patron['password'];

        // Check if the stored password is hashed (assuming it starts with $2y$ if hashed by bcrypt)
        if (password_verify($password, $storedPassword) || $storedPassword === $password) {
            // Password matches, whether hashed or plain
            $_SESSION['loginStatus'] = "Logged In Successfully.";
            $_SESSION['loginColor'] = "green";
            $_SESSION['email'] = $emailadd;
            $_SESSION['patrons_id'] = $patron['patrons_id'];
            header("Location: userpage.php");
            exit();
        } else {
            // Password is incorrect
            $_SESSION['loginStatus'] = "Invalid Password";
            $_SESSION['loginColor'] = "red";
            $_SESSION['loginPassBorderColor'] = "red;";
            $_SESSION['loginEmailBorderColor'] = "";
        }
    } else {
        // Email not found
        $_SESSION['loginStatus'] = "Invalid Email";
        $_SESSION['loginColor'] = "red";
        $_SESSION['loginEmailBorderColor'] = "red;";
        $_SESSION['loginPassBorderColor'] = "";
    }
}


// if (isset($_POST['login'])) {
//     $emailadd = $_POST['email'];
//     $password = $_POST['password'];

//     // Prepare and execute the PDO query
//     $query = "SELECT * FROM patrons WHERE email = :email";
//     $stmt = $pdo->prepare($query);
//     $stmt->bindParam(':email', $emailadd);
//     $stmt->execute();
//     $patron = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($patron) {
//         // Verify the hashed password
//         if (password_verify($password, $patron['password'])) {
//             $_SESSION['loginStatus'] = "Logged In Successfully.";
//             $_SESSION['loginColor'] = "green";
//             $_SESSION['email'] = $emailadd;
//             $_SESSION['patrons_id'] = $patron['patrons_id'];
//             header("Location: userpage.php");
//             exit();
//         } else {
//             // Password is incorrect
//             $_SESSION['loginStatus'] = "Invalid Password";
//             $_SESSION['loginColor'] = "red";
//             $_SESSION['loginPassBorderColor'] = "red;";
//             $_SESSION['loginEmailBorderColor'] = "";
//         }
//     } else {
//         // Email not found
//         $_SESSION['loginStatus'] = "Invalid Email";
//         $_SESSION['loginColor'] = "red";
//         $_SESSION['loginEmailBorderColor'] = "red;";
//         $_SESSION['loginPassBorderColor'] = "";
//     }
// }
?>



<?php
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
} else {
    $message = '';
}
?>



<body>
    <div class="wrapper">

        <div class="container-top">
            <div class="row row-between-top">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="../images/makati-logo.png" class="image">
                    </div>
                    Makati City Hall Library
                </div>


                <div class="container-navigation">

                    <a href="homepage.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="login.php" class="navigation-contents">LOG IN</a>

                    <a href="signup.php" class="navigation-contents">SIGN UP</a>

                </div>

            </div>
        </div>


        <div class="row-body">

            <div class="container-content row-center">

                <div class="container-login row">

                    <div class="container-login-left">

                        <div class="container-left-image">
                            <img src="../images/makati-logo.png" class="image">
                        </div>

                        <div class="left-description">
                            Log in now to explore our available books and discover more about the library!
                        </div>

                    </div>

                    <div class="container-login-right">

                        <form action="" method="POST">

                            <div class="container-form">

                                <div class="container-success" id="container-success" data-message="<?php echo $message; ?>" style="display: none;">
                                    <div class="container-success-description"><?php echo $message; ?></div>
                                    <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                                </div>

                                <div class="login-title">
                                    Login
                                </div>

                                <div>
                                    <?php
                                    if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus'] != '') {
                                        $containerClass = ($_SESSION['loginColor'] == 'green') ? 'alert-success' : 'alert-danger';
                                    ?>
                                        <div id="status-container" class="<?php echo $containerClass; ?>">
                                            <?php echo $_SESSION['loginStatus']; ?>
                                            <button type="button" class="close-btn" onclick="closeStatus()">&times;</button>
                                        </div>
                                    <?php
                                        unset($_SESSION['loginStatus']);
                                        unset($_SESSION['loginColor']);
                                    }
                                    ?>


                                    <script>
                                        function closeStatus() {
                                            var status = document.getElementById("status-container");
                                            status.style.display = "none";
                                        }
                                    </script>

                                </div>

                                <div class="container-input-100">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-100">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="input-text" autocomplete="off"
                                        required>
                                </div>

                                <div class="row row-right">
                                    <button type="submit" name="login" class="button button-submit">Login</button>
                                </div>

                                <div class="row-center">
                                    <a href="signup.php" class="link link-16px">
                                        Don't have an account?
                                    </a>
                                </div>

                                <div class="row-center">
                                    <a href="forgot.php" class="link link-16px">
                                        Forgot password
                                    </a>
                                </div>

                            </div>

                        </form>


                    </div>

                </div>



            </div>
        </div>



        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>


    </div>
</body>



</html>


<script src="js/close-status.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the container and its data attribute
        var successContainer = document.getElementById('container-success');
        var message = successContainer.getAttribute('data-message');

        // Check if the message is not empty
        if (message && message.trim() !== '') {
            // Display the container if there's a message
            successContainer.style.display = 'flex';
        } else {
            // Hide the container if there's no message
            successContainer.style.display = 'none';
        }
    });

</script>