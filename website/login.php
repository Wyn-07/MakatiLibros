<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php
include '../connection.php';

session_start();

if (isset($_POST['login'])) {
    $emailadd = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the PDO query for patron details
    $query = "SELECT * FROM patrons WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $emailadd);
    $stmt->execute();
    $patron = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patron) {
        $storedPassword = $patron['password'];
        $applicationStatus = $patron['application_status'];

        // Verify password
        if (password_verify($password, $storedPassword) || $storedPassword === $password) {
            if ($applicationStatus === "Approved") {
                // Approved: Allow login
                $_SESSION['email'] = $emailadd;
                $_SESSION['patrons_id'] = $patron['patrons_id'];
                
                header("Location: userpage.php");
                exit();
            } elseif ($applicationStatus === "Pending") {
                // Pending: Application not approved
                $_SESSION['loginStatus'] = "Your application is still pending approval by the librarian.";
            } elseif ($applicationStatus === "Rejected") {
                // Rejected: Show rejection reason
                $_SESSION['loginStatus'] = "Application rejected. Reason: " . $patron['application_status_reason'];
            } elseif ($applicationStatus === "Renewal") {
                // Check the `valid_until` field in the patrons_library_id table
                $query_library = "SELECT valid_until FROM patrons_library_id WHERE patrons_id = :patrons_id";
                $stmt_library = $pdo->prepare($query_library);
                $stmt_library->bindParam(':patrons_id', $patron['patrons_id'], PDO::PARAM_INT);
                $stmt_library->execute();
                $library = $stmt_library->fetch(PDO::FETCH_ASSOC);

                if ($library) {
                    $validUntil = new DateTime($library['valid_until']);
                    $currentDate = new DateTime();
                    $gracePeriod = (clone $validUntil)->modify('+1 month');

                    if ($currentDate <= $gracePeriod) {
                        // Within the grace period: Allow login
                        $_SESSION['email'] = $emailadd;
                        $_SESSION['patrons_id'] = $patron['patrons_id'];
                        
                        header("Location: userpage.php");
                        exit();
                    } else {
                        // Exceeded the grace period
                        $_SESSION['loginStatus'] = "Your library card has expired, and your renewal is still pending. Please wait for further notice.";
                    }
                } else {
                    // No library card found
                    $_SESSION['loginStatus'] = "No library card information found. Please contact the librarian.";
                }
            }
        } else {
            $_SESSION['loginStatus'] = "Invalid Password.";
        }
    } else {
        $_SESSION['loginStatus'] = "Invalid Email.";
    }
}
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
                        <img src="../images/library-logo.png" class="image">
                    </div>
                    Makati City Hall Library
                </div>



                <div class="container-navigation">

                    <a href="../index.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="login.php" class="navigation-contents">LOG IN</a>

                    <a href="signup.php" class="navigation-contents">SIGN UP</a>

                </div>

                <style>
                    .menu-hidden {
                        display:none;
                    }
                    .container-round.menu {
                        position: relative;
                        /* Allows the dropdown to be positioned relative to this button */
                        cursor: pointer;
                    }

                    /* Dropdown menu styling */
                    .menu-content {
                        display: none;
                        /* Hidden by default */
                        position: absolute;
                        top: 100%;
                        /* Positions dropdown right below the menu button */
                        right: 0;
                        /* Aligns the dropdown with the right side of the button */
                        background-color: white;
                        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                        padding: 10px;
                        z-index: 1;
                    }

                    .menu-content a {
                        color: black;
                        padding: 8px 16px;
                        text-decoration: none;
                        display: block;
                    }

                    .menu-content a:hover {
                        background-color: #ddd;
                    }

                    /* Show the menu when the 'menu-show' class is applied */
                    .menu-show {
                        display: block;
                    }
                </style>

                <div class="container-round menu-hidden" id="menuButton">
                    <img src="../images/expand-arrow-white.png" class="image">
                </div>

                <div id="dropdownMenu" class="menu-content">
                    <a href="homepage.php">HOME</a>
                    <a href="login.php">LOG IN</a>
                    <a href="signup.php">SIGN UP</a>
                </div>


                <script>
                    document.getElementById('menuButton').onclick = function() {
                        document.getElementById('dropdownMenu').classList.toggle('menu-show');
                    };
                </script>



                <script>
                    function toggleMenu() {
                        const dropdownMenu = document.getElementById('dropdownMenu');
                        dropdownMenu.classList.toggle('menu-show');
                    }
                </script>

            </div>
        </div>


        <!-- loading animation -->
        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>



        <div class="row-body">

            <div class="container-content row-center">

                <div class="container-login row form-row">

                    <div class="container-login-left">

                        <div class="container-left-image">
                            <img src="../images/library-logo.png" class="image">
                        </div>

                        <div class="left-description">
                            Log in now to explore our available books and discover more about the library!
                        </div>

                    </div>

                    <div class="container-login-right">

                        <form action="" method="POST">

                            <div class="container-form">



                                <div class="container-error" id="container-error" style="display: <?php echo isset($_SESSION['loginStatus']) ? 'flex' : 'none'; ?>;">
                                    <div class="container-error-description">
                                        <?php
                                        if (isset($_SESSION['loginStatus'])) {
                                            echo $_SESSION['loginStatus'];
                                            unset($_SESSION['loginStatus']);
                                        }
                                        ?>
                                    </div>
                                    <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                                </div>


                                <div class="container-success" id="container-success" style="display: <?php echo isset($_SESSION['signupStatus']) ? 'flex' : 'none'; ?>;">
                                    <div class="container-success-description">
                                        <?php
                                        if (isset($_SESSION['signupStatus'])) {
                                            echo $_SESSION['signupStatus'];
                                            unset($_SESSION['signupStatus']);
                                        }
                                        ?>
                                    </div>
                                    <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                                </div>

                                <div class="login-title">
                                    Login
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
<script src="js/loading-animation.js"></script>