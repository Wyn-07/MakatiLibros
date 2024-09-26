<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <title>Register</title>

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['inputpassword'];

    header("Location: setup.php?email=" . urlencode($email) . "&password=" . urlencode($password));
    exit();
}
?>

<body>
    <div class="wrapper">
        <div class="container-body-login">
            <div class="transparent">
                <div class="container-white-login">

                    <div class="row-center">
                        <div class="container-round login">
                            <img src="../images/makati-logo.png" alt="" class="image">
                        </div>
                        <div>
                            <div class="title-26px">
                                Register
                            </div>
                            <div class="font-size-16">
                                MakatiLibros
                            </div>
                        </div>
                    </div>

                    <form action="register.php" method="POST" id="signupForm">
                        <div class="container-form">

                            <div class="container-error" id="container-error" style="display: none;">
                                <div class="container-error-description">
                                    <span id="passwordError"></span>
                                </div>
                                <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                            </div>


                            <div class="container-input">
                                <div class="container-input-100">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="input-text" autocomplete="off" required>
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

                            </div>

                            <div class="row row-right">
                                <button type="submit" name="submit" class="button-submit">Register</button>
                            </div>
                        </div>
                    </form>

                    <div class="row-center">
                        <a href="login.php" class="login-link font-14px">Login</a>
                        <a href="reset.php" class="login-link  font-14px ">Reset Password</a>
                    </div>


                </div>
            </div>
        </div>

    </div>
</body>

</html>

<script>
    // Get the input elements
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

        // Update each requirement and style accordingly
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
            passwordError.textContent = "Please follow the requirements.";
            document.getElementById("container-error").style.display = "flex"; // Show error container
            document.getElementById("container-error").scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
            return false;

        } else if (password !== confirmPasswordValue) {
            passwordError.textContent = "Passwords do not match.";
            document.getElementById("container-error").style.display = "flex"; // Show error container
            document.getElementById("container-error").scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
            return false;

        } else {
            document.getElementById("container-error").style.display = "none"; // Hide error container if validation passes
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

    // Function to close the error status message
    function closeErrorStatus() {
        document.getElementById("container-error").style.display = "none";
    }
</script>