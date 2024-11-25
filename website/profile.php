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
                            View your personal information.
                        </div>
                        <div class="profile-subtitle-white">
                            Your details are displayed below.
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
                                            <div id="myAccount" class="profile-nav-items nav-profile">
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
                                            <div id="library_card" class="profile-nav-items">Application Renewal</div>
                                        </div>
                                    </a>

                                </div>


                            </div>


                        </div>


                        <div class="profile-container-right">


                            <div class="profile-container-right-white" id="myProfile">

                                <div class="container-column">

                                    <div>
                                        My Profile
                                    </div>
                                    <div class="container-profile-font-small">
                                        View your account details
                                    </div>
                                    <div style="padding:5px"></div>
                                </div>

                                <hr>


                                <div class="profile-row">

                                    <div class="right-contents-left">

                                        <div class="profile-row2">

                                            <div class="container-input-49">
                                                <label for="fname">First Name:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($firstname); ?> </div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="mname">Middle Name:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($middlename); ?> </div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="lname">Last Name:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($lastname); ?> </div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="suffix">Suffix:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($suffix); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="birthdate">Birthdate:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($birthdate); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="age">Age:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($age); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="gender">Gender:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($gender); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="contact">Contact:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($contact); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="house_num">House No./ Unit No. / Floor:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($house_num); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="building">Building:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($building); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="streets">Streets:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($streets); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="barangay">Barangay:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($barangay); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="company_name">Company Name:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($company_name); ?></div>
                                            </div>

                                            <div class="container-input-49">
                                                <label for="company_contact">Company Contact:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($company_contact); ?></div>
                                            </div>

                                            <div class="container-input-100">
                                                <label for="company_address">Company Address:</label>
                                                <div class="input-text"><?php echo htmlspecialchars($company_address); ?></div>
                                            </div>


                                        </div>

                                    </div>


                                    <div class="container-line">
                                        <div class="line"></div>
                                    </div>


                                    <div class="right-contents-right">

                                        <div style="display: flex; flex-direction: column;">

                                            <label for="profile_image">Profile Image:</label>

                                            <div class="container-profile-image2">
                                                <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image" id="imageProfilePreview">
                                            </div>

                                        </div>

                                    </div>


                                </div>


                                <div class="row row-between" style="padding: 10px 40px;">

                                    <div style="display: flex; flex-direction: column; width: 49%">

                                        <label for="valid_id">Valid ID:</label>
                                        <div class="container-profile-image3">
                                            <img src="../validID_images/<?php echo htmlspecialchars($valid_id); ?>" class="image" id="imageValidIDPreview">
                                        </div>
                                    </div>

                                    <div style="display: flex; flex-direction: column; width: 49%">
                                        <label for="sign">Sign:</label>
                                        <div class="container-profile-image3">
                                            <img src="../sign_images/<?php echo htmlspecialchars($sign); ?>" class="image" id="imageSignPreview">
                                        </div>
                                    </div>

                                </div>


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