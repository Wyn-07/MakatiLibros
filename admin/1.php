<div class='container-form'>
    <label for='book_image'>Book Image</label>
    <div class='container-form-book'>
        <div class='form-book'>
            <img src='../book_images/no_image.png' class='image'>
        </div>
    </div>
    <div class='container-input-100'>
        <label for='acc_num'>Acc Number</label>
        <input type='text' class='input-text' value='1234 md'>
    </div>
    <div class='container-input-100'>
        <label for='class_num'>Class Number</label>
        <input type='text' class='input-text' value='345'>
    </div>
    <div class='container-input-100'>
        <label for='title'>Book Title</label>
        <input type='text' class='input-text' value='Kupal ka ba boss'>
    </div>
    <div class='container-input-100'>
        <label for='author'>Author</label>
        <input type='text' class='input-text' value='Malupiton'>
    </div>
    <div class='container-input-100'>
        <label for='category'>Category</label>
        <input type='text' class='input-text' value='Circulation'>
    </div>
    <div class='container-input-100'>
        <label for='copyright'>Copyright</label>
        <input type='number' class='input-text' value='2024'>
    </div>
</div>





<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        try {
            // Start transaction to ensure both patron and guarantor data are saved together
            $pdo->beginTransaction();

            // Retrieve and sanitize patron input values
            $patronID = $_POST['patron_id'];
            $guarantorID = $_POST['guarantor_id'];

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
            $grtrfname = isset($_POST['guarantor_firstname']) ? $_POST['guarantor_firstname'] : '';
            $grtrmname = isset($_POST['guarantor_middlename']) ? $_POST['guarantor_middlename'] : '';
            $grtrlname = isset($_POST['guarantor_lastname']) ? $_POST['guarantor_lastname'] : '';
            $grtrsuffix = isset($_POST['guarantor_suffix']) ? $_POST['guarantor_suffix'] : '';
            $grtrcontact = isset($_POST['guarantor_contact']) ? $_POST['guarantor_contact'] : '';
            $grtraddress = isset($_POST['guarantor_address']) ? $_POST['guarantor_address'] : '';
            $grtrcompany_name = isset($_POST['guarantor_company_name']) ? $_POST['guarantor_company_name'] : '';
            $grtrcompany_contact = isset($_POST['guarantor_company_contact']) ? $_POST['guarantor_company_contact'] : '';
            $grtrcompany_address = isset($_POST['guarantor_company_address']) ? $_POST['guarantor_company_address'] : '';

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