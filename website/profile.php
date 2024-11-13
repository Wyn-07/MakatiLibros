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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

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


                    <div action="functions/update_profile.php" method="POST" enctype="multipart/form-data" id="form" class="row" onsubmit="return validateForm(['profile_image'], 'contact')">


                        <div class="profile-container-left">

                            <div class="profile-container-left-contents">

                                <div class="row">

                                    <div style="width: 20%">
                                        <div class="container-profile-image">
                                            <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image">
                                        </div>
                                    </div>

                                    <div style="width: 80%; padding:0 10px;">
                                        <div class="container-column">
                                            <div>
                                                Wyn Bacolod
                                                <!-- <?= $resident['first_name'] . " " . $resident['last_name'] ?> -->
                                            </div>
                                        </div>

                                        <div class="row container-profile-font-small profile-nav-items" onclick="toggleAccount()">
                                            <span class="">

                                            </span>Edit Profile
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
                                                My Account
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


                            </div>


                        </div>


                        <div class="profile-container-right" id="myProfile" style="display: block;">

                            <div class="container-column">
                                <div>
                                    My Profile
                                </div>
                                <div class="container-profile-font-small">
                                    Manage and protect your account
                                </div>
                                <div style="padding:5px"></div>
                            </div>

                            <hr>


                            <form action="profileForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

                                <div class="profile-row">

                                    <div class="right-contents-left">

                                        <div class="profile-row2">

                                            <input type="hidden" id="patronId" name="patron_id" value="<?php echo htmlspecialchars($patron_id); ?>">

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

                                            <div class="container-input-100">
                                                <div class="row">
                                                    <label for="address">Address:</label>
                                                    <div class="container-asterisk">
                                                        <img src="../images/asterisk-red.png" class="image">
                                                    </div>
                                                </div>
                                                <input type="text" id="address" name="address" class="input-text" value="<?php echo htmlspecialchars($address); ?>" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" required>
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

                                        <div class="container-column6">

                                            <div class="container-column4">

                                                <div style="display: flex; flex-direction: column; align-items: center;">
                                                    <div class="container-profile-image2">
                                                        <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image" id="imageProfilePreview">
                                                    </div>
                                                    <input type="file" class="profile-file" id="fileInput" name="profile" accept='.png, .jpg, .jpeg' onchange="previewImage(event, 'picturePreview')">
                                                </div>



                                                <div class="row row-right">
                                                    <button type="submit" id="save" name="save" value="save" class="button button-submit">Save</button>
                                                </div>

                                            </div>

                                        </div>

                                    </div>






                                </div>

                            </form>


                        </div>


                


                        <div class="profile-container-right" id="myNotification" style="display: none;">

                            <div class="container-column">
                                <div>
                                    My Notification
                                </div>
                                <div class="container-profile-font-small">
                                    Manage and protect your account
                                </div>
                                <div style="padding:5px"></div>
                            </div>

                            <hr>


                            <div class="container-profile-white">

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



<!-- script for toggle -->
<script>
    function toggleSection(sectionId) {
        // Hide all sections
        document.getElementById("myProfile").style.display = "none";
        document.getElementById("myTransaction").style.display = "none";
        document.getElementById("myNotification").style.display = "none";

        // Show the selected section
        document.getElementById(sectionId).style.display = "block";
    }
</script>


<script>
    function toggleStatus(statusId) {
        // Hide all sections
        document.getElementById("statusPending").style.display = "none";
        document.getElementById("statusAccepted").style.display = "none";
        document.getElementById("statusReturned").style.display = "none";

        // Show the selected section
        document.getElementById(statusId).style.display = "block";
    }
</script>

<!-- fetching transaction status -->
<!-- <script>
    document.querySelectorAll('.item-status').forEach(item => {
        item.addEventListener('click', function() {
            const status = this.getAttribute('data-status');

            // Send AJAX request to fetch books based on the status
            fetchBooksByStatus(status);
        });
    });

    function fetchBooksByStatus(status) {
        // Send the status to the server using fetch
        fetch('functions/fetch_books_by_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `status=${encodeURIComponent(status)}`
            })
            .then(response => response.text())
            .then(data => {
                // Display the fetched data in the #booksContainer
                document.getElementById('booksContainer').innerHTML = data;

                // After the new content is added, apply the star rating logic
                updateStarRatings();
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to update star ratings
    function updateStarRatings() {
        // Get all the star rating containers
        const starRatings = document.querySelectorAll('.star-rating');

        starRatings.forEach(function(starRating) {
            // Log the entire row to inspect its structure
            const row = starRating.closest('.row');
            console.log('Row:', row);

            // Get the average rating for each book from the hidden books-ratings div
            const booksRatingsElement = row.querySelector('.books-ratings');

            // Log the booksRatingsElement to see if it's being selected correctly
            console.log('booksRatingsElement:', booksRatingsElement);

            // If booksRatingsElement exists, proceed with fetching the avgRating
            if (booksRatingsElement) {
                const avgRating = parseFloat(booksRatingsElement.textContent);

                // Log the avgRating to the console for debugging
                console.log('Average Rating:', avgRating);

                // Loop through all stars and add the 'active' class based on avgRating
                const stars = starRating.querySelectorAll('.star');
                stars.forEach(function(star) {
                    const starValue = parseInt(star.getAttribute('data-value'));

                    // Add 'active' class if the star's value is less than or equal to the avgRating
                    if (starValue <= avgRating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }
        });
    }
</script> -->