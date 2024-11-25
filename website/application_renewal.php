<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Renewal</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>



<?php

session_start();

include '../connection.php';

include 'functions/fetch_guarantor.php';

date_default_timezone_set('Asia/Manila');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        try {


            // Retrieve and sanitize patron input values
            $patronIDS = $_POST['patron_id'];
            $guarantorIDS = $_POST['guarantor_id'];



            // Check if the patron is already in 'Renewal' status
            $checkStatusSql = "
             SELECT p.application_status, pli.valid_until 
             FROM patrons p
             LEFT JOIN patrons_library_id pli ON p.patrons_id = pli.patrons_id
             WHERE p.patrons_id = :patron_id
             ORDER BY pli.valid_until DESC
             LIMIT 1"; // Fetch the most recent `valid_until` date
            $checkStatusStmt = $pdo->prepare($checkStatusSql);
            $checkStatusStmt->bindParam(':patron_id', $patronIDS, PDO::PARAM_INT);
            $checkStatusStmt->execute();
            $patronData = $checkStatusStmt->fetch(PDO::FETCH_ASSOC);

            if ($patronData) {
                $currentStatus = $patronData['application_status'];
                $validUntil = $patronData['valid_until'];

                $currentDate = date('Y-m-d'); // Get today's date
                $formattedValidUntil = date('Y-m-d', strtotime($validUntil));


                // Check if the patron is already in 'Renewal' status
                if ($currentStatus === 'Renewal') {
                    $_SESSION['error_message'] = 'You have already applied for renewal. Please wait for approval.';
                    $_SESSION['error_display'] = 'flex';
                    header('Location: application_renewal.php');
                    exit();
                } elseif ($formattedValidUntil >= $currentDate) {
                    $_SESSION['error_message'] = 'Your library card is still valid until ' . $validUntil . '. No need to renew at this time.';
                    $_SESSION['error_display'] = 'flex';
                    header('Location: application_renewal.php');
                    exit();
                }

            } else {
                $_SESSION['error_message'] = 'Invalid patron ID or no library card found. Please try again.';
                $_SESSION['error_display'] = 'flex';
                header('Location: application_renewal.php');
                exit();
            }


            // Start transaction to ensure both patron and guarantor data are updated together
            $pdo->beginTransaction();

            $fname = $_POST['firstname'];
            $mname = $_POST['middlename'];
            $lname = $_POST['lastname'];
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
            $application_status = "Renewal";
            $application_status_reason = "";

            // Initialize image variable
            $imageName = null;

            // Process the image
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['profile_image'];
                $imageTmpName = $image['tmp_name'];

                // Extract current date and time
                $currentDateTime = date('Ymd_His');

                // Define the image name format: "patronid_lastname_date_time"
                $imageName = $patronIDS . '_' . $lname . '_' . $currentDateTime . '.jpg';

                // Set the target directory and file path
                $targetDir = '../patron_images/';
                $targetFilePath = $targetDir . $imageName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
                    $_SESSION['error_message'] = 'Failed to upload image.';
                    $_SESSION['error_display'] = 'flex';
                    header('Location: application_renewal.php');
                    exit();
                }
            }

            $validIdName = null;

            // Process the image
            if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
                $valid_id = $_FILES['valid_id'];
                $imageTmpName = $valid_id['tmp_name'];

                // Extract current date and time
                $currentDateTime = date('Ymd_His');

                // Define the image name format: "patronid_lastname_date_time"
                $validIdName = $patronIDS . '_' . $lname . '_' . $currentDateTime . '.jpg';

                // Set the target directory and file path
                $targetDir = '../validID_images/';
                $targetFilePath = $targetDir . $validIdName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
                    $_SESSION['error_message'] = 'Failed to upload image.';
                    $_SESSION['error_display'] = 'flex';
                    header('Location: application_renewal.php');
                    exit();
                }
            }


            $signName = null;

            // Process the image
            if (isset($_FILES['patron_sign']) && $_FILES['patron_sign']['error'] === UPLOAD_ERR_OK) {
                $sign = $_FILES['patron_sign'];
                $imageTmpName = $sign['tmp_name'];

                // Extract current date and time
                $currentDateTime = date('Ymd_His');

                // Define the image name format: "patronid_lastname_date_time"
                $signName = $patronIDS . '_' . $lname . '_' . $currentDateTime . '.jpg';

                // Set the target directory and file path
                $targetDir = '../sign_images/';
                $targetFilePath = $targetDir . $signName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
                    $_SESSION['error_message'] = 'Failed to upload image.';
                    $_SESSION['error_display'] = 'flex';
                    header('Location: application_renewal.php');
                    exit();
                }
            }



            // Update patron data
            $sql = "UPDATE patrons 
                    SET firstname = :firstname, middlename = :middlename, lastname = :lastname, suffix = :suffix, 
                        birthdate = :birthdate, age = :age, gender = :gender, contact = :contact, house_num = :house_num, 
                        building = :building, streets = :streets, barangay = :barangay, company_name = :company_name, 
                        company_contact = :company_contact, company_address = :company_address" .
                (!empty($imageName) ? ", image = :image" : "") .
                (!empty($validIdName) ? ", valid_id = :valid_id" : "") .
                (!empty($signName) ? ", sign = :sign" : "") . ",
                        application_status = :application_status, application_status_reason = :application_status_reason
                    WHERE patrons_id = :patrons_id";

            $stmt = $pdo->prepare($sql);

            // Bind patron parameters
            $stmt->bindParam(':patrons_id', $patronIDS, PDO::PARAM_INT);
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

            // Bind conditional parameters
            if (!empty($imageName)) {
                $stmt->bindParam(':image', $imageName);
            }
            if (!empty($validIdName)) {
                $stmt->bindParam(':valid_id', $validIdName);
            }
            if (!empty($signName)) {
                $stmt->bindParam(':sign', $signName);
            }

            // Bind remaining parameters
            $stmt->bindParam(':application_status', $application_status);
            $stmt->bindParam(':application_status_reason', $application_status_reason);


            // Execute patron update
            $stmt->execute();

            // Retrieve and sanitize guarantor input values
            $grtrfname = isset($_POST['guarantor_firstname']) ? $_POST['guarantor_firstname'] : '';
            $grtrmname = isset($_POST['guarantor_middlename']) ? $_POST['guarantor_middlename'] : '';
            $grtrlname = isset($_POST['guarantor_lastname']) ? $_POST['guarantor_lastname'] : '';
            $grtrsuffix = isset($_POST['guarantor_suffix']) ? $_POST['guarantor_suffix'] : '';
            $grtrcontact = isset($_POST['guarantor_contact']) ? $_POST['guarantor_contact'] : '';
            $grtraddress = isset($_POST['guarantor_address']) ? $_POST['guarantor_address'] : '';
            $grtrcompany_name = isset($_POST['guarantor_company_name']) ? $_POST['guarantor_company_name'] : '';
            $grtrcompany_contact = isset($_POST['guarantor_company_contact']) ? $_POST['guarantor_company_contact'] : '';
            $grtrcompany_address = isset($_POST['guarantor_company_address']) ? $_POST['guarantor_company_address'] : '';


            $guarantorSignName = null;

            // Process the image
            if (isset($_FILES['guarantor_sign']) && $_FILES['guarantor_sign']['error'] === UPLOAD_ERR_OK) {
                $gsign = $_FILES['guarantor_sign'];
                $imageTmpName = $gsign['tmp_name'];

                // Extract current date and time
                $currentDateTime = date('Ymd_His');

                // Define the image name format: "patronid_lastname_date_time"
                $guarantorSignName = $guarnatorIDS . '_' . $grtrlname . '_' . $currentDateTime . '.jpg';

                // Set the target directory and file path
                $targetDir = '../sign_images/';
                $targetFilePath = $targetDir . $guarantorSignName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($imageTmpName, $targetFilePath)) {
                    $_SESSION['error_message'] = 'Failed to upload image.';
                    header('Location: application_renewal.php');
                    exit();
                }
            }



            // Update guarantor data
            $sql_guarantor = "UPDATE guarantor 
                                SET firstname = :grtrfname, middlename = :grtrmname, lastname = :grtrlname, 
                                    suffix = :grtrsuffix, contact = :grtrcontact, address = :grtraddress, 
                                    company_name = :grtrcompany_name, company_contact = :grtrcompany_contact, 
                                    company_address = :grtrcompany_address" . (!empty($guarantorSignName) ? ", sign = :guarantor_sign" : "") . " 
                                WHERE guarantor_id = :guarantor_id";
            $stmt_guarantor = $pdo->prepare($sql_guarantor);

            // Bind guarantor parameters
            $stmt_guarantor->bindParam(':guarantor_id', $guarantorIDS, PDO::PARAM_INT);
            $stmt_guarantor->bindParam(':grtrfname', $grtrfname);
            $stmt_guarantor->bindParam(':grtrmname', $grtrmname);
            $stmt_guarantor->bindParam(':grtrlname', $grtrlname);
            $stmt_guarantor->bindParam(':grtrsuffix', $grtrsuffix);
            $stmt_guarantor->bindParam(':grtrcontact', $grtrcontact);
            $stmt_guarantor->bindParam(':grtraddress', $grtraddress);
            $stmt_guarantor->bindParam(':grtrcompany_name', $grtrcompany_name);
            $stmt_guarantor->bindParam(':grtrcompany_contact', $grtrcompany_contact);
            $stmt_guarantor->bindParam(':grtrcompany_address', $grtrcompany_address);

            if (!empty($guarantorSignName)) {
                $stmt_guarantor->bindParam(':guarantor_sign', $guarantorSignName);
            }


            // Execute guarantor update
            $stmt_guarantor->execute();

            // Commit transaction
            $pdo->commit();

            // Success message and redirect
            $_SESSION['success_message'] = 'Submitted successfully.';
            $_SESSION['success_display'] = 'flex';
            header("Location: application_renewal.php");
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
                            Application Renewal
                        </div>
                        <div class="profile-subtitle-white">
                            Renew your library membership seamlessly.
                        </div>
                        <div class="profile-subtitle-white">
                            Stay connected to all library resources and benefits.
                        </div>
                    </div>
                </div>




                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>



                <div class="profile-contents">


                    <div class="row">


                        <div class="profile-container-left">

                            <div class="profile-container-left-contents">

                                <div class="row">

                                    <div style="width: 20%">
                                        <div class="container-profile-image">
                                            <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image">
                                        </div>
                                    </div>

                                    <div style="width: 80%; padding:0 10px;">
                                        <div class="container-column" style="padding: 10px;">
                                            <div>
                                                <?php echo htmlspecialchars($firstname); ?> <?php echo htmlspecialchars($lastname); ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <hr>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/patrons-black.png" class="image" alt="">
                                    </div>

                                    <div class="container-column">
                                        <a href="profile.php">
                                            <div id="myAccount" class="profile-nav-items">
                                                My Profile
                                            </div>
                                        </a>
                                    </div>

                                </div>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/guarantors-black.png" class="image" alt="">
                                    </div>

                                    <div class="container-column">
                                        <a href="guarantor.php">
                                            <div id="myGuarantor" class="profile-nav-items">
                                                My Guarantor
                                            </div>
                                        </a>
                                    </div>

                                </div>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/transaction-black.png" class="image" alt="">
                                    </div>

                                    <a href="transaction.php">
                                        <div class="container-column">
                                            <div id="transaction" class="profile-nav-items" onclick="toggleSection('myTransaction')">My Book Transaction</div>
                                        </div>
                                    </a>

                                </div>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/bookmark-black.png" class="image" alt="">
                                    </div>

                                    <a href="favorites.php">
                                        <div class="container-column">
                                            <div id="favorites" class="profile-nav-items" onclick="toggleSection('myFavorites')">My Favorites</div>
                                        </div>
                                    </a>

                                </div>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/notification-black.png" class="image" alt="">
                                    </div>

                                    <a href="notification.php">
                                        <div class="container-column">
                                            <div id="notification" class="profile-nav-items" onclick="toggleSection('myNotification')">Notification</div>
                                        </div>
                                    </a>


                                </div>


                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/id-black.png" class="image" alt="">
                                    </div>

                                    <a href="library_card.php">
                                        <div class="container-column">
                                            <div id="library_card" class="profile-nav-items">Library Card</div>
                                        </div>
                                    </a>

                                </div>



                                <div class="profile-row">

                                    <div class="icon-profile">
                                        <img src="../images/application-black.png" class="image" alt="">
                                    </div>

                                    <a href="application_renewal.php">
                                        <div class="container-column">
                                            <div id="library_card" class="profile-nav-items nav-application-renewal">Application Renewal</div>
                                        </div>
                                    </a>

                                </div>


                            </div>

                        </div>




                        <div style="width: 100%;">

                            <div class="profile-container-right-white" id="myProfile">

                                <div>


                                    <div id="container-error" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                unset($_SESSION['error_display']); ?>;">
                                        <div class="container-error">
                                            <div class="container-error-description">
                                                <?php if (isset($_SESSION['error_message'])) {
                                                    echo $_SESSION['error_message'];
                                                    unset($_SESSION['error_message']);
                                                } ?>
                                            </div>
                                            <button type="button" class="button-success-close" onclick="closeErrorStatus()">&times;</button>
                                        </div>

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
                                </div>

                                <div class="container-column">

                                    <div>
                                        Application Renewal
                                    </div>
                                    <div class="container-profile-font-small">
                                        Apply for renewal
                                    </div>
                                    <div style="padding:5px"></div>

                                </div>

                                <hr>

                                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm(['profile_image'], 'contact')">

                                    <div class="profile-row">

                                        <div class="right-contents-left">

                                            <div class="profile-row2">

                                                <input type="hidden" id="patronId" name="patron_id" value="<?php echo htmlspecialchars($patron_id); ?>">
                                                <input type="hidden" id="guarantorId" name="guarantor_id" value="<?php echo htmlspecialchars($guarantor_id); ?>">

                                                <div class="container-input-49">
                                                    <div class="row">
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
                                                    <div class="row">
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
                                                    <div class="row">
                                                        <label for="birthdate">Birthdate:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="date" id="birthdate" name="birthdate" class="input-text" value="<?php echo htmlspecialchars($birthdate); ?>" autocomplete="off" onchange="calculateAge()" required>
                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="age">Age:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="number" id="age" name="age" class="input-text" value="<?php echo htmlspecialchars($age); ?>" autocomplete="off" required>
                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
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
                                                    <div class="row">
                                                        <label for="contact">Contact:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="contact" name="contact" class="input-text" value="<?php echo htmlspecialchars($contact); ?>" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="house_num">House No./ Unit No. / Floor:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="house_num" name="house_num" class="input-text" value="<?php echo htmlspecialchars($house_num); ?>" autocomplete="off" required>
                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="building">Building:</label>
                                                    </div>
                                                    <input type="text" id="building" name="building" class="input-text" value="<?php echo htmlspecialchars($building); ?>" autocomplete="off">
                                                </div>


                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="streets">Streets:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="streets" name="streets" class="input-text" value="<?php echo htmlspecialchars($streets); ?>" autocomplete="off" required>
                                                </div>


                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="barangay">Barangay</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <select class="input-text" id="barangay" name="barangay" required>
                                                        <option value="" disabled selected>Select Barangay</option>
                                                        <option value="Bangkal" <?php echo $barangay === 'Bangkal' ? 'selected' : ''; ?>>Bangkal</option>
                                                        <option value="Bel-Air" <?php echo $barangay === 'Bel-Air' ? 'selected' : ''; ?>>Bel-Air</option>
                                                        <option value="Carmona" <?php echo $barangay === 'Carmona' ? 'selected' : ''; ?>>Carmona</option>
                                                        <option value="Dasmariñas" <?php echo $barangay === 'Dasmariñas' ? 'selected' : ''; ?>>Dasmariñas</option>
                                                        <option value="Forbes Park" <?php echo $barangay === 'Forbes Park' ? 'selected' : ''; ?>>Forbes Park</option>
                                                        <option value="Guadalupe Nuevo" <?php echo $barangay === 'Guadalupe Nuevo' ? 'selected' : ''; ?>>Guadalupe Nuevo</option>
                                                        <option value="Guadalupe Viejo" <?php echo $barangay === 'Guadalupe Viejo' ? 'selected' : ''; ?>>Guadalupe Viejo</option>
                                                        <option value="Kasilawan" <?php echo $barangay === 'Kasilawan' ? 'selected' : ''; ?>>Kasilawan</option>
                                                        <option value="La Paz" <?php echo $barangay === 'La Paz' ? 'selected' : ''; ?>>La Paz</option>
                                                        <option value="Magallanes" <?php echo $barangay === 'Magallanes' ? 'selected' : ''; ?>>Magallanes</option>
                                                        <option value="Olympia" <?php echo $barangay === 'Olympia' ? 'selected' : ''; ?>>Olympia</option>
                                                        <option value="Palanan" <?php echo $barangay === 'Palanan' ? 'selected' : ''; ?>>Palanan</option>
                                                        <option value="Pinagkaisahan" <?php echo $barangay === 'Pinagkaisahan' ? 'selected' : ''; ?>>Pinagkaisahan</option>
                                                        <option value="Pio del Pilar" <?php echo $barangay === 'Pio del Pilar' ? 'selected' : ''; ?>>Pio del Pilar</option>
                                                        <option value="Poblacion" <?php echo $barangay === 'Poblacion' ? 'selected' : ''; ?>>Poblacion</option>
                                                        <option value="San Antonio" <?php echo $barangay === 'San Antonio' ? 'selected' : ''; ?>>San Antonio</option>
                                                        <option value="San Isidro" <?php echo $barangay === 'San Isidro' ? 'selected' : ''; ?>>San Isidro</option>
                                                        <option value="San Lorenzo" <?php echo $barangay === 'San Lorenzo' ? 'selected' : ''; ?>>San Lorenzo</option>
                                                        <option value="Singkamas" <?php echo $barangay === 'Singkamas' ? 'selected' : ''; ?>>Singkamas</option>
                                                        <option value="Sta. Cruz" <?php echo $barangay === 'Sta. Cruz' ? 'selected' : ''; ?>>Sta. Cruz</option>
                                                        <option value="Tejeros" <?php echo $barangay === 'Tejeros' ? 'selected' : ''; ?>>Tejeros</option>
                                                        <option value="Urdaneta" <?php echo $barangay === 'Urdaneta' ? 'selected' : ''; ?>>Urdaneta</option>
                                                        <option value="Valenzuela" <?php echo $barangay === 'Valenzuela' ? 'selected' : ''; ?>>Valenzuela</option>
                                                    </select>

                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="company_name">Company Name:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="company_name" name="company_name" class="input-text" value="<?php echo htmlspecialchars($company_name); ?>" autocomplete="off" required>
                                                </div>

                                                <div class="container-input-49">
                                                    <div class="row">
                                                        <label for="company_contact">Company Contact:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="company_contact" name="company_contact" class="input-text" value="<?php echo htmlspecialchars($company_contact); ?>" autocomplete="off" required>
                                                </div>

                                                <div class="container-input-100">
                                                    <div class="row">
                                                        <label for="company_address">Company Address:</label>
                                                        <div class="container-asterisk">
                                                            <img src="../images/asterisk-red.png" class="image">
                                                        </div>
                                                    </div>
                                                    <input type="text" id="company_address" name="company_address" class="input-text" value="<?php echo htmlspecialchars($company_address); ?>" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                                </div>

                                            </div>

                                        </div>


                                        <div class="container-line">
                                            <div class="line"></div>
                                        </div>


                                        <div class="right-contents-right">

                                            <div style="display: flex; flex-direction: column;">
                                                <div class="row">
                                                    <label for="profile_image">Profile Image:</label>
                                                    <div class="container-asterisk">
                                                        <img src="../images/asterisk-red.png" class="image">
                                                    </div>
                                                </div>

                                                <div class="container-profile-image2">
                                                    <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image" id="imageProfilePreview">
                                                </div>
                                                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="profile-file" onchange="previewProfileImage(event)">
                                            </div>

                                        </div>

                                    </div>


                                    <div class="row row-between" style="padding: 10px 40px;">

                                        <div style="display: flex; flex-direction: column; width: 49%">
                                            <div class="row">
                                                <label for="valid_id">Valid ID:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <div class="container-profile-image3">
                                                <img src="../validID_images/<?php echo htmlspecialchars($valid_id); ?>" class="image" id="imageValidIDPreview">
                                            </div>
                                            <input type="file" name="valid_id" id="valid_id" accept="image/*" class="profile-file2" onchange="previewValidIDImage(event)">
                                        </div>

                                        <div style="display: flex; flex-direction: column; width: 49%">
                                            <div class="row">
                                                <label for="patron_sign">Sign:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <div class="container-profile-image3">
                                                <img src="../sign_images/<?php echo htmlspecialchars($sign); ?>" class="image" id="imageSignPreview">
                                            </div>
                                            <input type="file" name="patron_sign" id="patron_sign" accept="image/*" class="profile-file2" onchange="previewSignImage(event)">
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


                                    <div class="profile-row2">

                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="guarantor_firstname">First Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_firstname" name="guarantor_firstname" class="input-text" value="<?php echo htmlspecialchars($guarantor_firstname); ?>" autocomplete="off" oninput="capitalize(this)" required>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_middlename">Middle Name:</label>
                                            <input type="text" id="guarantor_middlename" name="guarantor_middlename" class="input-text" value="<?php echo htmlspecialchars($guarantor_middlename); ?>" oninput="capitalize(this)" autocomplete="off">
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="guarantor_lastname">Last Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_lastname" name="guarantor_lastname" class="input-text" value="<?php echo htmlspecialchars($guarantor_lastname); ?>" oninput="capitalize(this)" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_suffix">Suffix:</label>
                                            <input type="text" id="guarantor_suffix" name="guarantor_suffix" class="input-text" value="<?php echo htmlspecialchars($guarantor_suffix); ?>" autocomplete="off" oninput="capitalize(this)">
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="guarantor_contact">Contact:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_contact" name="guarantor_contact" class="input-text" value="<?php echo htmlspecialchars($guarantor_contact); ?>" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="guarantor_address">Address:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_address" name="guarantor_address" class="input-text" value="<?php echo htmlspecialchars($guarantor_address); ?>" autocomplete="off" required>
                                        </div>


                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="guarantor_company_name">Guarantor Company Name:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_company_name" name="guarantor_company_name" class="input-text" value="<?php echo htmlspecialchars($guarantor_company_name); ?>" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-49">
                                            <div class="row">
                                                <label for="grtrcompany_contact">Company Contact:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_company_contact" name="guarantor_company_contact" class="input-text" value="<?php echo htmlspecialchars($guarantor_company_contact); ?>" autocomplete="off" required>
                                        </div>

                                        <div class="container-input-100">
                                            <div class="row">
                                                <label for="guarantor_company_address">Company Address:</label>
                                                <div class="container-asterisk">
                                                    <img src="../images/asterisk-red.png" class="image">
                                                </div>
                                            </div>
                                            <input type="text" id="guarantor_company_address" name="guarantor_company_address" class="input-text" value="<?php echo htmlspecialchars($guarantor_company_address); ?>" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                        </div>


                                        <div class="row" style="padding-top: 10px;">

                                            <div style="display: flex; flex-direction: column; width: 49%">
                                                <div class="row">
                                                    <label for="guarantor_sign">Guarantor Sign:</label>
                                                    <div class="container-asterisk">
                                                        <img src="../images/asterisk-red.png" class="image">
                                                    </div>
                                                </div>
                                                <div class="container-profile-image3">
                                                    <img src="../sign_images/<?php echo htmlspecialchars($guarantor_sign); ?>" class="image" id="imageGuarantorSignPreview">
                                                </div>
                                                <input type="file" name="guarantor_sign" id="guarantor_sign" accept="image/*" class="profile-file2" onchange="previewGuarantorSignImage(event)">
                                            </div>

                                        </div>


                                    </div>



                                    <div class="row row-right" style="padding-top: 40px;">
                                        <button type="submit" id="submit" name="submit" value="submit" class="button button-submit">Submit</button>
                                    </div>


                                </form>


                            </div>
                        </div>

                    </div>

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


    function previewValidIDImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageValidIDPreview = document.getElementById('imageValidIDPreview');
            imageValidIDPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function previewSignImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageSignPreview = document.getElementById('imageSignPreview');
            imageSignPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function previewGuarantorSignImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageSignPreview = document.getElementById('imageGuarantorSignPreview');
            imageSignPreview.src = reader.result;
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