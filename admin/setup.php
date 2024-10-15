<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <title>Setup</title>

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
</head>


<?php
include '../connection.php';

session_start();

if (isset($_GET['email'])) {
    $get_email = htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8');
}

if (isset($_GET['password'])) {
    $get_password = htmlspecialchars($_GET['password'], ENT_QUOTES, 'UTF-8');
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Retrieve and sanitize input values
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
        $suffix = $_POST['suffix'];
        $birthdate = $_POST['birthdate'];

        $birthdateFormatted = date('m/d/Y', strtotime($birthdate));

        $age = (int)$_POST['age'];
        $gender = $_POST['gender'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO librarians (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, email, password)
                VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :address, :email, :password)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':firstname', $fname);
        $stmt->bindParam(':middlename', $mname);
        $stmt->bindParam(':lastname', $lname);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':birthdate', $birthdateFormatted);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        try {
            $stmt->execute();
            $_SESSION['success_message'] = "Registered successfully.";
            $_SESSION['success_display'] = "flex";
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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
                            <img src="../images/makati-logo.png" alt="" class="image">
                        </div>
                        <div>
                            <div class="title-26px">
                                Set up
                            </div>
                            <div class="font-size-16">
                                MakatiLibros
                            </div>
                        </div>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                        <div class="container-form">

                            <div class="container-input">

                                <input type="text" id="email" name="email" value="<?php echo $get_email ?>" required style="display:none">
                                <input type="text" id="password" name="password" value="<?php echo $get_password ?>" required style="display:none">

                                <div class="container-input-49">
                                    <div class="row row-between">
                                        <label for="fname">First Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="fname" name="fname" class="input-text" autocomplete="off" oninput="capitalize(this)" required>
                                </div>

                                <div class="container-input-49">
                                    <label for="mname">Middle Name:</label>
                                    <input type="text" id="mname" name="mname" class="input-text" oninput="capitalize(this)" autocomplete="off">
                                </div>

                                <div class="container-input-49">
                                    <div class="row row-between">
                                        <label for="lname">Last Name:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="lname" name="lname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                                </div>

                                <div class="container-input-49">
                                    <label for="suffix">Suffix:</label>
                                    <input type="text" id="suffix" name="suffix" class="input-text" autocomplete="off" oninput="capitalize(this)">
                                </div>

                                <div class="container-input-49">
                                    <div class="row row-between">
                                        <label for="birthdate">Birthdate:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="date" id="birthdate" name="birthdate" class="input-text" autocomplete="off" onchange="calculateAge()" required>
                                </div>

                                <div class="container-input-49">
                                    <div class="row row-between">
                                        <label for="age">Age:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="number" id="age" name="age" class="input-text" autocomplete="off" required>
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
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="LGBTQ+">LGBTQ+</option>
                                    </select>
                                </div>

                                <div class="container-input-49">
                                    <div class="row row-between">
                                        <label for="contact">Contact:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="contact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                                </div>

                                <div class="container-input-100">
                                    <div class="row row-between">
                                        <label for="address">Address:</label>
                                        <div class="container-asterisk">
                                            <img src="../images/asterisk-red.png" class="image">
                                        </div>
                                    </div>
                                    <input type="text" id="address" name="address" class="input-text" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
                                </div>

                            </div>



                            <div class="row row-right">
                                <button type="submit" name="submit" class="button-submit">Submit</button>
                            </div>
                        </div>
                    </form>

                    <div class="row-center">
                        <a href="register.php" class="login-link font-14px">Register</a>
                        <a href="reset.php" class="login-link  font-14px ">Reset Password</a>
                    </div>


                </div>
            </div>
        </div>

    </div>
</body>

</html>


<script src="js/input-validation.js"></script>