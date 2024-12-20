<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Setup</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>


<?php session_start() ?>

<?php include '../connection.php'; ?>

<?php include 'functions/fetch_category.php'; ?>



<?php
if (isset($_GET['email'])) {
    $get_email = htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8');
}

if (isset($_GET['password'])) {
    $get_password = htmlspecialchars($_GET['password'], ENT_QUOTES, 'UTF-8');
}
?>


<?php

// Function to sanitize the string for the image name
function sanitizeFileName($string)
{
    // Remove special characters, allowing only alphanumeric characters
    return preg_replace('/[^A-Za-z0-9]/', '', $string); // Allow only alphanumeric characters
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        try {
            // Start transaction to ensure both patron and guarantor data are saved together
            $pdo->beginTransaction();

            // Retrieve and sanitize patron input values
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
            $fname = $_POST['fname'];
            $mname = $_POST['mname'];
            $lname = $_POST['lname'];
            $suffix = $_POST['suffix'];
            $birthdate = $_POST['birthdate'];
            $age = (int)$_POST['age'];
            $gender = $_POST['gender'];
            $contact = $_POST['contact'];
            $house_num = $_POST['house_num'];
            $building = $_POST['building'];
            $streets = $_POST['streets'];
            $barangay = $_POST['barangay'];
            $company_name = $_POST['company_name'];
            $company_contact = $_POST['company_contact'];
            $company_address = $_POST['company_address'];
            $interests = isset($_POST['categories']) ? implode(",", $_POST['categories']) : '';
            $application_status = "Pending";
            $application_status_reason = "";

            // Process the image
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['profile_image'];
                $imageTmpName = $image['tmp_name'];

                // Sanitize the filename
                $currentDateTime = date('Ymd_His');
                $lnameSanitize = sanitizeFileName(trim($lname));

                // Define the image name format
                $imageName = $lnameSanitize . '_' . $currentDateTime . '.jpg';

                // Set the target directory and file path
                $targetDir = '../patron_images/';
                $targetFilePath = $targetDir . $imageName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                    // echo "Image uploaded successfully: " . $imageName;
                } else {
                    $_SESSION['error_message'] = 'Failed to upload image.';
                    header('Location: signup.php');
                    exit();
                }
            }

            if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
                $valid_id = $_FILES['valid_id'];
                $imageTmpName = $valid_id['tmp_name'];

                // Sanitize the filename
                $validIDName = $lnameSanitize . '_' . $currentDateTime . '_validID.jpg';

                // Set the target directory and file path
                $targetDir = '../validID_images/';
                $targetFilePath = $targetDir . $validIDName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                    echo "Valid ID uploaded successfully: " . $validIDName;
                } else {
                    $_SESSION['error_message'] = 'Failed to upload valid ID.';
                    header('Location: signup.php');
                    exit();
                }
            }

            if (isset($_FILES['patron_sign']) && $_FILES['patron_sign']['error'] === UPLOAD_ERR_OK) {
                $patron_sign = $_FILES['patron_sign'];
                $imageTmpName = $patron_sign['tmp_name'];

                // Sanitize the filename
                $patronSign = $lnameSanitize . '_' . $currentDateTime . '_sign.jpg';

                // Set the target directory and file path
                $targetDir = '../sign_images/';
                $targetFilePath = $targetDir . $patronSign;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                    // echo "Valid ID uploaded successfully: " . $validIDName;
                } else {
                    $_SESSION['error_message'] = 'Failed to upload valid ID.';
                    header('Location: signup.php');
                    exit();
                }
            }



            // Prepare SQL to insert patron data
            $sql = "INSERT INTO patrons (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, house_num, building, streets, barangay, company_name, company_contact, company_address, interest, email, password, image, valid_id, sign, application_status, application_status_reason)
                    VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :house_num, :building, :streets, :barangay, :company_name, :company_contact, :company_address, :interest, :email, :password, :image, :valid_id, :sign, :application_status, :application_status_reason)";

            $stmt = $pdo->prepare($sql);

            // Bind patron parameters
            $stmt->bindParam(':firstname', $fname);
            $stmt->bindParam(':middlename', $mname);
            $stmt->bindParam(':lastname', $lname);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':house_num', $house_num);
            $stmt->bindParam(':building', $building);
            $stmt->bindParam(':streets', $streets);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':company_contact', $company_contact);
            $stmt->bindParam(':company_address', $company_address);
            $stmt->bindParam(':interest', $interests);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':image', $imageName);
            $stmt->bindParam(':valid_id', $validIDName);
            $stmt->bindParam(':sign', $patronSign);
            $stmt->bindParam(':application_status', $application_status);
            $stmt->bindParam(':application_status_reason', $application_status_reason);

            // Execute patron insert
            $stmt->execute();

            // Get last inserted patron ID
            $patrons_id = $pdo->lastInsertId();

            // Retrieve and sanitize guarantor input values
            $grtrfname = isset($_POST['grtrfname']) ? $_POST['grtrfname'] : '';
            $grtrmname = isset($_POST['grtrmname']) ? $_POST['grtrmname'] : '';
            $grtrlname = isset($_POST['grtrlname']) ? $_POST['grtrlname'] : '';
            $grtrsuffix = isset($_POST['grtrsuffix']) ? $_POST['grtrsuffix'] : '';
            $grtrcontact = isset($_POST['grtrcontact']) ? $_POST['grtrcontact'] : '';
            $grtraddress = isset($_POST['grtraddress']) ? $_POST['grtraddress'] : '';
            $grtrcompany_name = isset($_POST['grtrcompany_name']) ? $_POST['grtrcompany_name'] : '';
            $grtrcompany_contact = isset($_POST['grtrcompany_contact']) ? $_POST['grtrcompany_contact'] : '';
            $grtrcompany_address = isset($_POST['grtrcompany_address']) ? $_POST['grtrcompany_address'] : '';

            if (isset($_FILES['guarantor_sign']) && $_FILES['guarantor_sign']['error'] === UPLOAD_ERR_OK) {
                $guarantor_sign = $_FILES['guarantor_sign'];
                $imageTmpName = $guarantor_sign['tmp_name'];

                // Sanitize the filename
                $currentDateTime = date('Ymd_His');
                $grtrlnameSanitize = sanitizeFileName(trim($grtrlname));

                // Sanitize the filename
                $guarantorSign = $grtrlnameSanitize . '_' . $currentDateTime . '_sign.jpg';

                // Set the target directory and file path
                $targetDir = '../sign_images/';
                $targetFilePath = $targetDir . $guarantorSign;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                    // echo "Valid ID uploaded successfully: " . $validIDName;
                } else {
                    $_SESSION['error_message'] = 'Failed to upload valid ID.';
                    header('Location: signup.php');
                    exit();
                }
            }

            // Prepare SQL to insert guarantor data using patron ID
            $sql_guarantor = "INSERT INTO guarantor (patrons_id, firstname, middlename, lastname, suffix, contact, address, company_name, company_contact, company_address, sign)
                  VALUES (:patrons_id, :grtrfname, :grtrmname, :grtrlname, :grtrsuffix, :grtrcontact, :grtraddress, :grtrcompany_name, :grtrcompany_contact, :grtrcompany_address, :sign)";

            // Prepare the statement
            $stmt_guarantor = $pdo->prepare($sql_guarantor);

            // Bind parameters
            $stmt_guarantor->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
            $stmt_guarantor->bindParam(':grtrfname', $grtrfname);
            $stmt_guarantor->bindParam(':grtrmname', $grtrmname);
            $stmt_guarantor->bindParam(':grtrlname', $grtrlname);
            $stmt_guarantor->bindParam(':grtrsuffix', $grtrsuffix);
            $stmt_guarantor->bindParam(':grtrcontact', $grtrcontact);
            $stmt_guarantor->bindParam(':grtraddress', $grtraddress);
            $stmt_guarantor->bindParam(':grtrcompany_name', $grtrcompany_name);
            $stmt_guarantor->bindParam(':grtrcompany_contact', $grtrcompany_contact);
            $stmt_guarantor->bindParam(':grtrcompany_address', $grtrcompany_address);
            $stmt_guarantor->bindParam(':sign', $guarantorSign);


            // Execute guarantor insert
            $stmt_guarantor->execute();

            // Commit transaction
            $pdo->commit();

            // Success message and redirect
            $_SESSION['signupStatus'] = "Submitted successfully. Please wait 24 hours for your application to be approved. The approval will be sent to your email.";
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            // Rollback if any error occurs
            $pdo->rollBack();
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

                    <a href="../index.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="login.php" class="navigation-contents">LOG IN</a>

                    <a href="signup.php" class="navigation-contents">SIGN UP</a>

                </div>

            </div>
        </div>



        <div class="row-body">


            <!-- loading animation -->
            <div id="loading-overlay">
                <div class="spinner"></div>
            </div>


            <div class="container-content row-center">


                <div class="container-consent container-column" id="consent">

                    <div style="display:flex; flex-direction:column; gap:15px;  padding:50px; font-size: 16px;">

                        <div style="font-size: 20px; text-align:center; font-weight: bold ">
                            DATA PRIVACY CONSENT
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            In Compliance with the Data Privacy Act (DPA/R.P 10173) of 2012, and its implementing Rules and Regulations (IRR) effective since September 8, 2016, I allow the MAKATI CITY LIBRARY under the EDUCATION DEPARTMENT of the City Government of Makati to provide me certain services in relation to my application for the Makati City Library Card.
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            As such, I agree and authorize them to:
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            1. Collect and use my personal information for the purpose stated above and any other legal purposes.
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            2. Retain and store my information for a certain period as prescribed by law. My information will be deleted/destroyed after this period.
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            3. Share my information with other offices/departments within the City Government of Makati and necessary third parties for legitimate purposes. I am assured that security systems are employed to protect my information.
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            4. Allow only myself or a duly authorized representative (with a Special Power of Attorney) to view, change, or recover my personal information.
                        </div>

                        <div style="font-size: 16px; text-align:justify">
                            5. Inform me of future services or projects offered by the City Government of Makati using the personal information I shared.
                        </div>

                        <div class="row row-center" style="margin:10px">
                            <a href="signup.php">
                                <div class="button button-black">Cancel</div>
                            </a>
                            <div class="button button-submit" onclick="toggleContainers()">Agree</div>
                        </div>

                    </div>



                </div>


                <div class="container-login row container-login-white" style="display: none" id="setupForm">

                    <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateInterestForm()">

                        <div class="container-form" id="setup">

                            <div class="container-error" id="container-error" style="display: none">
                                <div class="container-error-description" id="message"></div>
                                <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
                            </div>

                            <div class="login-title">
                                Set up your profile
                            </div>


                            <div class="container-input">

                                <input type="text" id="email" name="email" value="<?php echo $get_email ?>" required style="display:none">
                                <input type="text" id="password" name="password" value="<?php echo $get_password ?>" required style="display:none">

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="fname">First Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="fname" name="fname" class="input-text" autocomplete="off" oninput="capitalize(this)" required>
                                </div>

                                <div class="container-input-40">
                                    <label for="mname">Middle Name:</label>
                                    <input type="text" id="mname" name="mname" class="input-text" oninput="capitalize(this)" autocomplete="off">
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="lname">Last Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="lname" name="lname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <label for="suffix">Suffix:</label>
                                    <input type="text" id="suffix" name="suffix" class="input-text" autocomplete="off" oninput="capitalize(this)">
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="birthdate">Birthdate:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="date" id="birthdate" name="birthdate" class="input-text" autocomplete="off" onchange="calculateAge()" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="age">Age:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="number" id="age" name="age" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="gender">Gender</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <select class="input-text" id="gender" name="gender" required>
                                        <option value="" disabled selected> </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="LGBTQ+">LGBTQ+</option>
                                    </select>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="contact">Contact:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="contact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="house_num">House No./ Unit No. / Floor:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="house_num" name="house_num" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="building">Building:</label>
                                    </div>
                                    <input type="text" id="building" name="building" class="input-text" autocomplete="off">
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="streets">Streets:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="streets" name="streets" class="input-text" autocomplete="off" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="barangay">Barangay</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <select class="input-text" id="barangay" name="barangay" required>
                                        <option value="" disabled selected>Select Barangay</option>
                                        <option value="Bangkal">Bangkal</option>
                                        <option value="Bel-Air">Bel-Air</option>
                                        <option value="Carmona">Carmona</option>
                                        <option value="Dasmariñas">Dasmariñas</option>
                                        <option value="Forbes Park">Forbes Park</option>
                                        <option value="Guadalupe Nuevo">Guadalupe Nuevo</option>
                                        <option value="Guadalupe Viejo">Guadalupe Viejo</option>
                                        <option value="Kasilawan">Kasilawan</option>
                                        <option value="La Paz">La Paz</option>
                                        <option value="Magallanes">Magallanes</option>
                                        <option value="Olympia">Olympia</option>
                                        <option value="Palanan">Palanan</option>
                                        <option value="Pinagkaisahan">Pinagkaisahan</option>
                                        <option value="Pio del Pilar">Pio del Pilar</option>
                                        <option value="Poblacion">Poblacion</option>
                                        <option value="San Antonio">San Antonio</option>
                                        <option value="San Isidro">San Isidro</option>
                                        <option value="San Lorenzo">San Lorenzo</option>
                                        <option value="Singkamas">Singkamas</option>
                                        <option value="Sta. Cruz">Sta. Cruz</option>
                                        <option value="Tejeros">Tejeros</option>
                                        <option value="Urdaneta">Urdaneta</option>
                                        <option value="Valenzuela">Valenzuela</option>
                                    </select>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="company_name">Company Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="company_name" name="company_name" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="company_contact">Company Contact:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="company_contact" name="company_contact" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-81">
                                    <div class="row">
                                        <label for="company_address">Company Address:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="company_address" name="company_address" class="input-text" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="profile_image">Profile Image</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <div class="container-signup-image">
                                        <img src="../patron_images/default_image.png" class="image-contain" id="imageProfilePreview">
                                    </div>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="signup-file" onchange="previewProfileImage(event)" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="valid_id">Valid ID:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <div class="container-signup-image">
                                        <img src="../images/valid_id.png" class="image-contain" id="imageValidIDPreview">
                                    </div>
                                    <input type="file" name="valid_id" id="valid_id" accept="image/*" class="signup-file" onchange="previewValidIDImage(event)" required>
                                </div>

                            </div>

                            <br>

                            <div class="row">

                                <div class="login-title">
                                    Set up your guarantor
                                </div>

                                <div class="login-subtitle">
                                    (Guarantor is liable to replace lost/unreturned books of the applicant)
                                </div>

                            </div>

                            <div class="container-input">

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtrfname">Guarantor's First Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrfname" name="grtrfname" class="input-text" autocomplete="off" oninput="capitalize(this)" required>
                                </div>

                                <div class="container-input-40">
                                    <label for="grtrmname">Guarantor's Middle Name:</label>
                                    <input type="text" id="grtrmname" name="grtrmname" class="input-text" oninput="capitalize(this)" autocomplete="off">
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtrlname">Guarantor's Last Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrlname" name="grtrlname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <label for="grtrsuffix">Guarantor's Suffix:</label>
                                    <input type="text" id="grtrsuffix" name="grtrsuffix" class="input-text" autocomplete="off" oninput="capitalize(this)">
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtrcontact">Guarantor's Contact:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrcontact" name="grtrcontact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtraddress">Guarantor's Address:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtraddress" name="grtraddress" class="input-text" autocomplete="off" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtrcompany_name">Guarantor's Company Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrcompany_name" name="grtrcompany_name" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="grtrcompany_contact">Guarantor's Company Contact:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrcompany_contact" name="grtrcompany_contact" class="input-text" autocomplete="off" required>
                                </div>

                                <div class="container-input-81">
                                    <div class="row">
                                        <label for="grtrcompany_address">Guarantor's Company Address:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="grtrcompany_address" name="grtrcompany_address" class="input-text" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                </div>

                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="guarantor_sign">Guarantor Signature</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <div class="container-signup-image">
                                        <img src="../images/white-bg.jfif" class="image-contain" id="imageGuarantorSignPreview">
                                    </div>
                                    <input type="file" name="guarantor_sign" id="guarantor_sign" accept="image/*" class="signup-file" onchange="previewGuarantorSignImage(event)" required>
                                </div>


                                <div class="container-input-40">
                                    <div class="row">
                                        <label for="patron_sign">Patron Signature</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <div class="container-signup-image">
                                        <img src="../images/white-bg.jfif" class="image-contain" id="imagePatronSignPreview">
                                    </div>
                                    <input type="file" name="patron_sign" id="patron_sign" accept="image/*" class="signup-file" onchange="previewPatronSignImage(event)" required>
                                </div>

                                <div class="container-input-81">
                                    <div class="row row-right">
                                        <div class="button button-black" onclick="if (validateForm()) toggleInterest();">Next</div>
                                    </div>
                                </div>




                            </div>





                            <div class="row-center">
                                <a href="login.php" class="link link-16px">
                                    Already have an account?
                                </a>
                            </div>

                            <div class="row-center">
                                <a href="forgot.php" class="link link-16px">
                                    Forgot password
                                </a>
                            </div>

                        </div>



                        <div class="container-form" id="interest" style="display:none">


                            <div class="container-error" id="error-message" style="display: none">
                                Please select at least 3 categories before submitting.
                                <button type="button" class="button-error-close" onclick="closeErrorInterest()">&times;</button>
                            </div>



                            <div class="login-title">
                                Select Atleast Three Category you are interested
                            </div>

                            <br>

                            <div class="container-input-category">

                                <?php foreach ($categories as $category => $description): ?>

                                    <div class="container-interest">

                                        <label class="category-title" for="<?php echo $category; ?>"> <?php echo $category; ?></label>

                                        <div class="container-interest-image" onclick="toggleCheckbox('<?php echo $category; ?>')">
                                            <!-- <img src="path/to/category-images/<?php echo $category; ?>.jpg" class="image"> -->
                                            <img src="../images/no-image.png" class="image-contain" id="image-<?php echo $category; ?>">
                                        </div>

                                        <div>
                                            <input type="checkbox" id="<?php echo $category; ?>" name="categories[]" value="<?php echo $category; ?>" style="display:none">
                                            <p class="category-description"><?php echo $description; ?></p>
                                        </div>
                                    </div>

                                <?php endforeach; ?>


                            </div>


                            <div class="row row-between">
                                <button name="back" class="button button-black" onclick="toggleSetup()">Back</button>

                                <button type="submit" name="submit" class="button button-submit">Sign up</button>
                            </div>


                        </div>



                    </form>






                    </form>





                </div>



            </div>

        </div>


        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>


        </button>
    </div>
</body>



</html>


<script>
    function toggleContainers() {
        // Get the consent and setup form containers
        const consentContainer = document.getElementById('consent');
        const setupFormContainer = document.getElementById('setupForm');

        // Hide consent container and show setup form container
        consentContainer.style.display = 'none';
        setupFormContainer.style.display = 'block';
    }
</script>



<script>
    function previewProfileImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageProfilePreview = document.getElementById('imageProfilePreview');
            imageProfilePreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewValidIDImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageValidPreview = document.getElementById('imageValidIDPreview');
            imageValidPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewGuarantorSignImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageGuarantorSignPreview = document.getElementById('imageGuarantorSignPreview');
            imageGuarantorSignPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function previewPatronSignImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imagePatronSignPreview = document.getElementById('imagePatronSignPreview');
            imagePatronSignPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script>
    function validateInterestForm() {
        var selectedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
        var errorMessage = document.getElementById("error-message");

        if (selectedCategories.length < 3) {
            // Show the error message
            errorMessage.style.display = "flex";

            return false; // Prevent form submission
        } else {
            // Hide the error message
            errorMessage.style.display = "none";

            // Remove the red border from each container
            document.querySelectorAll('.container-interest').forEach(function(container) {
                container.style.border = ""; // Reset the border
            });

            return true; // Allow form submission
        }
    }
    // Bind the form submission event to the validation function
    document.querySelector("form").onsubmit = function(event) {
        if (!validateInterestForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    };
</script>



<script src="js/input-validation.js"></script>
<script src="js/close-status.js"></script>
<script src="js/loading-animation.js"></script>

<script>
    var setup = document.getElementById("setup");
    var interest = document.getElementById("interest");


    function toggleSetup() {
        setup.style.display = "flex";
        interest.style.display = "none";
    }

    function toggleInterest() {
        setup.style.display = "none";
        interest.style.display = "flex";
    }
</script>



<script>
    function toggleCheckbox(categoryId) {
        var checkbox = document.getElementById(categoryId);
        var image = document.getElementById("image-" + categoryId);

        checkbox.checked = !checkbox.checked; // Toggle the checked state

        // Toggle the blur effect on the image
        if (checkbox.checked) {
            image.classList.add("image-blur");
        } else {
            image.classList.remove("image-blur");
        }
    }
</script>