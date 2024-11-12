<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>


<?php session_start() ?>

<body>
    <div class="wrapper">

        <div class="container-top">
            <?php include 'navbar.php'; ?>
        </div>

        <div id="overlay" class="overlay"></div>

        <div class="row-body-padding-0">

            <div class="container-sidebar" id="sidebar">
                <?php include 'sidebar.php'; ?>
            </div>


            <div class="container-content">

                <div class="container-profile">
                    <div class="transparent-profile">
                        <div class="profile-title-white">
                            User Profile

                        </div>
                        <div class="profile-subtitle-white">
                            View and manage your personal information.
                        </div>
                        <div class="profile-subtitle-white">
                            Keep your details up to date.
                        </div>
                    </div>
                </div>



                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>



                <div class="profile-contents">

                    <div class="profile-container">

                        <div class="container-error" id="container-error" style="display: none">
                            <div class="container-error-description" id="message"></div>
                            <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                        </div>



                        <div id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                    unset($_SESSION['success_display']); ?>;">
                            <div class="container-success">
                                <div class="container-success-description">
                                    <?php if (isset($_SESSION['success_message'])) {
                                        echo $_SESSION['success_message'];
                                        unset($_SESSION['success_message']);
                                    } ?>
                                </div>
                                <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                            </div>

                        </div>


                        <form action="functions/update_profile.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm(['profile_image'], 'contact')">

                            <div class="profile-container-contents">

                                <div class="profile-left">

                                    <div class="container-input">
                                        <input type="hidden" id="patronId" name="patron_id" value="<?php echo htmlspecialchars($patron_id); ?>">

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="fname">First Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="firstname" name="firstname" class="input-text" value="<?php echo htmlspecialchars($firstname); ?>" autocomplete="off" oninput="capitalize(this)" required>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="mname">Middle Name:</label>
                                            <input type="text" id="middlename" name="middlename" class="input-text" value="<?php echo htmlspecialchars($middlename); ?>" oninput="capitalize(this)" autocomplete="off">
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="lname">Last Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="lastname" name="lastname" class="input-text" value="<?php echo htmlspecialchars($lastname); ?>" oninput="capitalize(this)" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="suffix">Suffix:</label>
                                            <input type="text" id="suffix" name="suffix" class="input-text" value="<?php echo htmlspecialchars($suffix); ?>" autocomplete="off" oninput="capitalize(this)">
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="birthdate">Birthdate:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="date" id="birthdate" name="birthdate" class="input-text" value="<?php echo htmlspecialchars($birthdate); ?>" autocomplete="off" onchange="calculateAge()" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="age">Age:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="number" id="age" name="age" class="input-text" value="<?php echo htmlspecialchars($age); ?>" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="gender">Gender</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <select class="input-text" id="gender" name="gender" required>
                                                <option value="" disabled selected> </option>
                                                <option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                                                <option value="LGBTQ+" <?php echo $gender === 'LGBTQ+' ? 'selected' : ''; ?>>LGBTQ+</option>
                                            </select>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="contact">Contact:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="contact" name="contact" class="input-text" value="<?php echo htmlspecialchars($contact); ?>" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-100">
                                            <div class="row row-between">
                                                <label for="address">Address:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="address" name="address" class="input-text" value="<?php echo htmlspecialchars($address); ?>" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="company_name">Company Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="company_name" name="company_name" class="input-text" value="<?php echo htmlspecialchars($company_name); ?>" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row row-between">
                                                <label for="company_contact">Company Contact:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="company_contact" name="company_contact" class="input-text" value="<?php echo htmlspecialchars($company_contact); ?>" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-100">
                                            <div class="row row-between">
                                                <label for="company_address">Company Address:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="company_address" name="company_address" class="input-text" value="<?php echo htmlspecialchars($company_address); ?>" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                        </div>

                                    </div>

                                </div>

                                <div class="profile-right">

                                    <div>
                                        <div class="profile-image">
                                            <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image" id="imageProfilePreview">
                                        </div>
                                        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="input-text profile-select" onchange="previewProfileImage(event)">

                                    </div>

                                    <div class="row row-right">
                                        <button type="submit" name="submit" class="button button-submit">Update</button>
                                    </div>

                                </div>


                            </div>



                        </form>


                    </div>



                </div>



            </div>



        </div>






    </div>
</body>



</html>


<script src="js/banner.js"></script>
<script src="js/sidebar.js"></script>
<script src="js/close-status.js"></script>
<script src="js/loading-animation.js"></script>


<script>
    function previewProfileImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageHistoryPreview = document.getElementById('imageProfilePreview');
            imageHistoryPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }








    var firstNameInput = document.getElementById("firstname");
    var middleNameInput = document.getElementById("middlename");
    var lastNameInput = document.getElementById("lastname");
    var suffixInput = document.getElementById("suffix");

    function preventNumbersAndSpecialChars(event) {
        var inputValue = event.target.value;
        var newValue = inputValue.replace(/[^a-zA-Z\s]/g, ''); // Remove any character that is not a letter or space
        event.target.value = newValue;
    }

    function allowHypen(event) {
        var inputValue = event.target.value;
        var newValue = inputValue.replace(/[^a-zA-Z\s-]/g, ''); // Allow letters, spaces, and hyphens
        event.target.value = newValue;
    }

    function allowPeriod(event) {
        var inputValue = event.target.value;
        var newValue = inputValue.replace(/[^a-zA-Z\s.]/g, ''); // Allow letters, spaces, and hyphens
        event.target.value = newValue;
    }


    firstNameInput.addEventListener("input", preventNumbersAndSpecialChars);
    middleNameInput.addEventListener("input", allowHypen);
    lastNameInput.addEventListener("input", allowHypen);
    suffixInput.addEventListener("input", allowPeriod);


    var addressInput = document.getElementById("address");
    var companyAddressInput = document.getElementById("company_address");

    function preventSpecialChars(event) {
        var inputValue = event.target.value;
        // Allow letters, numbers, spaces, hyphens, and periods
        var newValue = inputValue.replace(/[^a-zA-Z0-9\s.-]/g, '');
        event.target.value = newValue;
    }


    addressInput.addEventListener("input", preventSpecialChars);
    companyAddressInput.addEventListener("input", preventSpecialChars);

    function capitalize(input) {
        var inputValue = input.value;
        var words = inputValue.split(' ');

        var capitalizedWords = words.map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });

        var capitalizedValue = capitalizedWords.join(' ');

        input.value = capitalizedValue;
    }

    function disableSpace(event) {
        var input = event.target;
        if (event.key === ' ' && input.selectionStart === 0) {
            event.preventDefault();
        }
    }



    function calculateAge() {
        var birthdateInput = document.getElementById('birthdate');
        var ageInput = document.getElementById('age');

        var birthdate = new Date(birthdateInput.value);
        var today = new Date();

        var age = today.getFullYear() - birthdate.getFullYear();

        // Adjust age if birthday hasn't occurred yet this year
        if (today.getMonth() < birthdate.getMonth() ||
            (today.getMonth() === birthdate.getMonth() && today.getDate() < birthdate.getDate())) {
            age--;
        }

        ageInput.value = age;
    }

    function handleInput(input) {
        if (!isNaN(input.value)) {
            input.value = input.value.replace(/\D/g, '');

            input.value = "+" + input.value;

            if (!input.value.startsWith("+639")) {
                input.value = "+639" + input.value.slice(4);
            }

            if (input.value.length > 13) {
                input.value = input.value.slice(0, 13);
            }
        } else {
            input.value = "+" + input.value.replace(/\D/g, '');
        }

    }



    function validateForm(fileInputs, contactInputId) {
        var resultErrorContainer = document.getElementById("container-error");
        var message = document.getElementById("message");
        message.innerHTML = "";

        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var isValid = true;

        // Validate file inputs
        fileInputs.forEach(function(filename) {
            var fileInput = document.getElementById(filename);
            var filePath = fileInput.value;

            if (!filePath) {
                return;
            }

            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                fileInput.style.border = '2px solid red'; // Highlight the invalid input
            } else {
                fileInput.style.border = ''; // Reset the border if valid
            }
        });

        // Validate contact input
        var contactInput = document.getElementById(contactInputId);
        if (contactInput.value.length < 13) {
            isValid = false; // Set valid to false if input is invalid
            contactInput.style.border = "2px solid red"; // Set the border to red
            resultErrorContainer.style.display = "flex"; // Show the error container
            message.innerHTML = "Contact number must be 13 characters long."; // Set the error message
            message.style.display = "block"; // Display the message
        } else {
            contactInput.style.border = ""; // Reset the border if valid
        }

        // Hide error messages if everything is valid
        if (isValid) {
            resultErrorContainer.style.display = "none"; // Hide error container if all inputs are valid
            message.style.display = "none"; // Hide message
        }

        return isValid; // Return true if all inputs are valid
    }
</script>