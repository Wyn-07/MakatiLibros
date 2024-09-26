<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $email = $_POST['email'];
    $password = $_POST['inputpassword']; // You should hash or securely handle this password if used

    // Redirect to setup.php with email and password as query parameters
    header("Location: setup.php?email=" . urlencode($email) . "&password=" . urlencode($password));
    exit();
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
                            Sign up today to explore our full collection and enjoy exclusive library benefits!
                        </div>

                    </div>

                    <div class="container-login-right">




                        <form action="signup.php" method="POST" id="signupForm">

                            <div class="container-form">

                                <div class="container-form-error" id="resultPasswordContainer" style="display: none">
                                    <div id="passwordError" class="container-error-description"></div>
                                    <button type="button" class="button-error-close" onclick="closePasswordStatus()">&times;</button>
                                </div>

                                <div class="login-title">
                                    Sign up
                                </div>

                                <div class="container-input-100">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="input-text" autocomplete="off" required>
                                </div>

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

                                <div class="row-center">
                                    <a href="signup.php" class="link link-16px">
                                        Already have an account?
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