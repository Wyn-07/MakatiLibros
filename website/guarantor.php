<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guarantor</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>


<?php session_start() ?>

<?php include '../connection.php'; ?>
<?php include 'functions/fetch_guarantor.php'; ?>

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
                            Guarantor Profile
                        </div>
                        <div class="profile-subtitle-white">
                            View your guarantor information.
                        </div>
                        <div class="profile-subtitle-white">
                            Your guarantor details are displayed below.
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
                                            <div id="myGuarantor" class="profile-nav-items nav-profile">
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
                                        My Guarantor
                                    </div>
                                    <div class="container-profile-font-small">
                                        View your guarantor details
                                    </div>
                                    <div style="padding:5px"></div>
                                </div>

                                <hr>


                                <div class="profile-row">

                                    <div class="profile-row2">

                                        <div class="container-input-49">
                                            <label for="guarantor_firstname">First Name:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_firstname); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_middlename">Middle Name:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_middlename); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_lastname">Last Name:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_lastname); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_suffix">Suffix:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_suffix); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_contact">Contact:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_contact); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_address">Address:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_address); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_company_name">Guarantor Company Name:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_company_name); ?></div>
                                        </div>

                                        <div class="container-input-49">
                                            <label for="guarantor_company_contact">Company Contact:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_company_contact); ?></div>
                                        </div>

                                        <div class="container-input-100">
                                            <label for="guarantor_company_address">Company Address:</label>
                                            <div class="input-text"><?php echo htmlspecialchars($guarantor_company_address); ?></div>
                                        </div>



                                        <div class="row" style="padding-top: 10px;">

                                            <div style="display: flex; flex-direction: column; width: 49%">
                                                <label for="sign">Sign:</label>
                                                <div class="container-profile-image3">
                                                    <img src="../sign_images/<?php echo htmlspecialchars($guarantor_sign); ?>" class="image" id="imageSignPreview">
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


    </div>


    </div>
</body>



</html>


<script src="js/banner.js"></script>
<script src="js/sidebar.js"></script>
<script src="js/close-status.js"></script>
<script src="js/loading-animation.js"></script>