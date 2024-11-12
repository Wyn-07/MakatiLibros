<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>




<?php

session_start();

include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Retrieve and sanitize input values
        $email = $_POST['email'];
        $password = password_hash($_POST['inputpassword'], PASSWORD_DEFAULT); // Hashing the password for security

        // Prepare the SQL statement to update the password for the given email
        $sql = "UPDATE patrons SET password = :password WHERE email = :email";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        try {
            // Execute the statement and check if any row was updated
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $_SESSION['signupStatus'] = "Password reset successfully.";
                header("Location: login.php");
                exit();
            } else {
                echo "Email not found. Please sign up.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
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

                    <a href="homepage.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="login.php" class="navigation-contents">LOG IN</a>

                    <a href="signup.php" class="navigation-contents">SIGN UP</a>

                </div>

            </div>
        </div>




        <div class="row-body">

            <div class="container-content row-center">

                <div class="container-login row form-row">

                    <div class="container-login-left">

                        <div class="container-left-image">
                            <img src="../images/library-logo.png" class="image">
                        </div>

                        <div class="left-description">
                            Forgot your password? Reset it here to regain access to your library account and continue exploring our collection!
                        </div>

                    </div>

                    <div class="container-login-right">


                        <form action="" method="POST" id="signupForm">

                            <div class="container-form">

                                <div class="container-form-error" id="resultPasswordContainer" style="display: none">
                                    <div id="passwordError" class="container-error-description"></div>
                                    <button type="button" class="button-error-close" onclick="closePasswordStatus()">&times;</button>
                                </div>


                                <div class="container-success" id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                                                        unset($_SESSION['success_display']); ?>">
                                    <div class="container-success-description" id="success-message">
                                        <?php
                                        if (isset($_SESSION['success_message'])) {
                                            echo $_SESSION['success_message'];
                                            unset($_SESSION['success_message']);
                                        }
                                        ?>
                                    </div>
                                    <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                                </div>

                                <div class="container-error" id="container-error" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                    unset($_SESSION['error_display']); ?>">
                                    <div class="container-error-description" id="error-message">
                                        <?php
                                        if (isset($_SESSION['error_message'])) {
                                            echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']);
                                        }
                                        ?>
                                    </div>
                                    <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                                </div>



                                <div class="login-title">
                                    Forgot Password
                                </div>


                                <!-- loading animation -->
                                <div id="loading-overlay">
                                    <div class="spinner"></div>
                                </div>


                                <div id="emailCont" style="margin-bottom: 50px">
                                    <div class="container-input-100">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="input-text" autocomplete="off" required>
                                    </div>

                                    <div class="row row-right">
                                        <button type="button" name="send" id="sendBtn" onclick="sendVerification()" class="button button-submit">Send Verification</button>
                                        <div id="timerDisplay" style="display: none; padding: 15px;">1:00</div>
                                        <button type="button" name="resend" id="resendBtn" onclick="resendVerification()" class="button button-submit" style="display: none;">Resend Verification</button>
                                    </div>


                                    <div id="randomInput" style="color: white"></div>

                                    <div class="container-input-100">
                                        <label for="code">Verification Code</label>
                                        <input type="text" id="verificationCode" name="code" class="input-text" autocomplete="off" required>
                                    </div>

                                    <div class="row row-right">
                                        <button type="button" onclick="verifyCode()" name="verify" class="button button-submit">Verify</button>
                                    </div>

                                </div>



                                <div id="passwordCont" style="display:none">
                                    <div class="container-input-100">
                                        <label for="inputpassword">Password</label>
                                        <input type="password" id="inputpassword" name="inputpassword" class="input-text" autocomplete="off" required>
                                    </div>

                                    <div class="container-input-100">
                                        <label for="confirmpassword">Confirm Password</label>
                                        <input type="password" id="confirmpassword" name="confirmpassword" class="input-text" autocomplete="off" required>
                                    </div>

                                    <div id="passwordRequirements" class="container-password-requirements">
                                        <div class="font-size-16">Password must contain:</div>
                                        <div class="font-size-14" id="letter">At least 1 letter</div>
                                        <div class="font-size-14" id="number">At least 1 number (0-9)</div>
                                        <div class="font-size-14" id="length">At least 8 character length</div>
                                        <div class="font-size-14" id="lowercase">At least 1 lowercase (a...z)</div>
                                        <div class="font-size-14" id="uppercase">At least 1 uppercase (A...Z)</div>
                                    </div>


                                    <div class="row row-right">
                                        <button type="submit" name="submit" class="button button-submit">Sign up</button>
                                    </div>
                                </div>







                                <div class="row-center">
                                    <a href="login.php" class="link link-16px">
                                        Already have an account?
                                    </a>
                                </div>

                                <div class="row-center">
                                    <a href="signup.php" class="link link-16px">
                                        Don't have an account?
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


<script src="js/loading-animation.js"></script>



<script>
    // Generate 4 random numbers
    let randomNumbers = [];
    for (let i = 0; i < 4; i++) {
        randomNumbers.push(Math.floor(Math.random() * 10)); // Generates a number between 0 and 9
    }

    // Join numbers as a single string and display in the div
    document.getElementById('randomInput').innerHTML = randomNumbers.join('');
</script>


<script>
    let countdownTimer;
    let timerDuration = 60; // 1 minute in seconds

    // Function to start the timer
    function startTimer() {
        const timerDisplay = document.getElementById('timerDisplay');
        const sendButton = document.getElementById('sendBtn');
        const resendButton = document.getElementById('resendBtn');

        // Hide the "Send Verification" button and show the "Resend Verification" button
        sendButton.style.display = 'none'; // Hide send button
        resendButton.style.display = 'inline-block'; // Show resend button
        resendButton.disabled = true; // Disable resend button initially

        // Show the timer
        timerDisplay.style.display = 'inline-block';

        // Start the countdown from the initial value
        countdownTimer = setInterval(function() {
            const minutes = Math.floor(timerDuration / 60);
            const seconds = timerDuration % 60;

            // Update the timer display
            timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            // When the timer reaches 0, stop the countdown and enable the resend button
            if (timerDuration <= 0) {
                clearInterval(countdownTimer); // Stop the timer
                resendButton.disabled = false; // Enable the resend button
                timerDisplay.style.display = 'none'; // Hide the timer when done
            } else {
                timerDuration--;
            }
        }, 1000); // Update every second
    }

    // Function to handle the resend functionality
    function resendVerification() {
        const resendButton = document.getElementById('resendBtn');
        const timerDisplay = document.getElementById('timerDisplay');

        // Reset the timer back to 1 minute (60 seconds)
        timerDuration = 60;

        // Show the timer again
        timerDisplay.style.display = 'inline-block';

        // Disable the resend button and start the countdown
        resendButton.disabled = true;

        // Start the countdown timer
        startTimer();
    }

    // Function to send verification
    function sendVerification() {
        const emailField = document.querySelector('input[name="email"]'); // Get the email input field
        const email = emailField.value;
        const verificationCode = document.getElementById('randomInput').innerText;
        const errorMessageContainer = document.getElementById('error-message'); // Get the error message container

        // Clear any previous error message
        errorMessageContainer.innerHTML = '';

        // Reset email field border color
        emailField.style.borderColor = '';

        // Check if email or verification code is missing
        if (!email || !verificationCode) {
            // Display the error message inside the error container if email is missing
            errorMessageContainer.innerHTML = 'Please fill in the email field.';
            document.getElementById('container-error').style.display = 'flex'; // Show the error message container
            document.getElementById('container-success').style.display = 'none'; // Hide the success container
            emailField.style.borderColor = 'red'; // Set the email field border color to red
            return;
        }

        const formData = new FormData();
        formData.append('email', email);
        formData.append('verificationCode', verificationCode);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_forgot_verification.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    document.getElementById('container-success').style.display = 'flex';
                    document.getElementById('success-message').innerHTML = response.message;
                    document.getElementById('container-error').style.display = 'none';
                    emailField.style.borderColor = ''; // Remove the red border if successful
                    startTimer(); // Start the timer once the verification is sent
                } else if (response.status === 'error') {
                    document.getElementById('container-error').style.display = 'flex';
                    document.getElementById('error-message').innerHTML = response.message;
                    document.getElementById('container-success').style.display = 'none';
                }
            }
        };
        xhr.send(formData);
    }









    function closeSuccessStatus() {
        document.getElementById('container-success').style.display = 'none';
    }

    function closeErrorStatus() {
        document.getElementById('container-error').style.display = 'none';
    }
</script>



<script>
    // Function to verify the verification code
    function verifyCode() {
        const userCode = document.getElementById('verificationCode').value; // Get the value from the input field
        const randomCode = document.getElementById('randomInput').innerText; // Get the verification code from randomInput
        const errorMessageContainer = document.getElementById('error-message'); // Get the error message container

        // Clear previous error message
        errorMessageContainer.innerHTML = '';

        // Check if the user input is blank
        if (!userCode) {
            errorMessageContainer.innerHTML = 'Please enter the verification code.';
            document.getElementById('container-error').style.display = 'flex'; // Show the error message container
            document.getElementById('container-success').style.display = 'none'; // Hide the success container
            document.getElementById('verificationCode').style.borderColor = 'red'; // Set border color to red
            return;
        }

        // Check if the entered code matches the generated code
        if (userCode !== randomCode) {
            errorMessageContainer.innerHTML = 'Incorrect verification code. Please try again.';
            document.getElementById('container-error').style.display = 'flex'; // Show the error message container
            document.getElementById('container-success').style.display = 'none'; // Hide the success container
            document.getElementById('verificationCode').style.borderColor = 'red'; // Set border color to red
        } else {
            // If the code matches, show the success message
            document.getElementById('container-success').style.display = 'flex';
            document.getElementById('success-message').innerHTML = 'Verification successful!';
            document.getElementById('container-error').style.display = 'none'; // Hide the error message container
            document.getElementById('verificationCode').style.borderColor = ''; // Remove red border color

            // Hide emailCont and show passwordCont
            document.getElementById('emailCont').style.display = 'none';
            document.getElementById('passwordCont').style.display = 'block';
        }
    }
</script>






<script>
    // Get the input element
    var inputPassword = document.getElementById("inputpassword");
    var confirmPassword = document.getElementById("confirmpassword");
    var resultPasswordContainer = document.getElementById("resultPasswordContainer");
    var passwordError = document.getElementById("passwordError");

    // Get the password strength requirements
    var numberRequirement = document.getElementById("number");
    var lengthRequirement = document.getElementById("length");
    var lowercaseRequirement = document.getElementById("lowercase");
    var uppercaseRequirement = document.getElementById("uppercase");
    var letterRequirement = document.getElementById("letter");

    // Function to check if a requirement is met
    function isRequirementMet(regex) {
        return regex.test(inputPassword.value);
    }

    // Add event listener for input event
    inputPassword.addEventListener("input", function() {
        var password = inputPassword.value;

        // Update the previous state of requirements
        var previousState = {
            number: isRequirementMet(/\d/),
            length: password.length >= 8,
            lowercase: isRequirementMet(/[a-z]/),
            uppercase: isRequirementMet(/[A-Z]/),
            letter: isRequirementMet(/[a-zA-Z]/)
        };

        // Update each requirement and update the style accordingly
        numberRequirement.style.color = previousState.number ? "green" : "";
        lengthRequirement.style.color = previousState.length ? "green" : "";
        lowercaseRequirement.style.color = previousState.lowercase ? "green" : "";
        uppercaseRequirement.style.color = previousState.uppercase ? "green" : "";
        letterRequirement.style.color = previousState.letter ? "green" : "";
    });

    // Function to validate the password fields
    function validatePassword() {
        var password = inputPassword.value;
        var confirmPasswordValue = confirmPassword.value;
        var allRequirementsMet = true;

        // Check each requirement and mark in red if not followed
        if (!/\d/.test(password)) {
            numberRequirement.style.color = "red";
            allRequirementsMet = false;
        }
        if (password.length < 8) {
            lengthRequirement.style.color = "red";
            allRequirementsMet = false;
        }
        if (!/[a-z]/.test(password)) {
            lowercaseRequirement.style.color = "red";
            allRequirementsMet = false;
        }
        if (!/[A-Z]/.test(password)) {
            uppercaseRequirement.style.color = "red";
            allRequirementsMet = false;
        }
        if (!/[a-zA-Z]/.test(password)) {
            letterRequirement.style.color = "red";
            allRequirementsMet = false;
        }

        if (!allRequirementsMet) {
            resultPasswordContainer.style.display = "flex";
            passwordError.textContent = "Please follow the requirements.";
            resultPasswordContainer.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
            return false;

        } else if (password !== confirmPasswordValue) {
            passwordError.textContent = "Passwords do not match.";
            resultPasswordContainer.style.display = "flex";
            resultPasswordContainer.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
            return false;

        } else {
            return true;
        }
    }

    // Attach the validation function to the form's submit event
    document.getElementById("signupForm").onsubmit = function(event) {
        if (!validatePassword()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    };

    // Function to close the password status message
    function closePasswordStatus() {
        resultPasswordContainer.style.display = "none";
    }
</script>