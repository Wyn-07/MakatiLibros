<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php include '../connection.php'; ?>

<?php include 'functions/fetch_categories.php'; ?>


<?php
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
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
        $suffix = $_POST['suffix'];
        $birthdate = $_POST['birthdate'];
        $age = (int)$_POST['age'];
        $gender = $_POST['gender'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        // Handle categories (interests)
        $interests = isset($_POST['categories']) ? implode(",", $_POST['categories']) : '';

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO users (firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, interest, email, password)
                VALUES (:firstname, :middlename, :lastname, :suffix, :birthdate, :age, :gender, :contact, :address, :interest, :email, :password)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':firstname', $fname);
        $stmt->bindParam(':middlename', $mname);
        $stmt->bindParam(':lastname', $lname);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':interest', $interests);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        try {
            $stmt->execute();
            header("Location: login.php?message=" . urlencode("Registered successfully."));
            exit(); 
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


                        <form action="" method="POST" id="form">

                            <div class="container-form" id="setup">

                                <div class="login-title">
                                    Set up your profile
                                </div>


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
                                        <input type="text" id="mname" name="mname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
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
                                        <input type="text" id="suffix" name="suffix" class="input-text" autocomplete="off" oninput="capitalize(this)" required>
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
                                    <div class="button button-black" onclick="toggleInterest()">Next</div>
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

                                <div class="login-title">
                                    Select Category you are interested
                                </div>


                                <div class="container-input">

                                    <?php foreach ($categories as $category): ?>

                                        <div class="container-interest">
                                            <div class="container-interest-image" onclick="toggleCheckbox('<?php echo $category; ?>')">
                                                <!-- <img src="path/to/category-images/<?php echo $category; ?>.jpg" class="image"> -->
                                                <img src="../images/no-image.png" class="image-contain" id="image-<?php echo $category; ?>">
                                            </div>
                                            <div>
                                                <input type="checkbox" id="<?php echo $category; ?>" name="categories[]" value="<?php echo $category; ?>" style="display:none">
                                                <label for="<?php echo $category; ?>"> <?php echo $category; ?></label>
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


                    </div>



                    </form>

                </div>



            </div>

        </div>


        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>

    </div>
</body>



</html>



<script src="js/input-validation.js"></script>

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