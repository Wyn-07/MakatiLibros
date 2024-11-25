<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Card</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>


<?php session_start() ?>

<?php include '../connection.php'; ?>

<?php include 'functions/fetch_library_card.php'; ?>


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
                            Library Card
                        </div>
                        <div class="profile-subtitle-white">
                            View your library card information.
                        </div>
                        <div class="profile-subtitle-white">
                            Access details about your borrowing privileges and membership status.
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
                                            <div id="library_card" class="profile-nav-items nav-library-card">Library Card</div>
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



                        <div style="width: 100%;">

                            <div class="profile-container-right-white">


                                <div class="container-column">

                                    <div>
                                        My Library Card
                                    </div>
                                    <div class="container-profile-font-small">
                                        View your library card
                                    </div>
                                    <div style="padding:5px"></div>
                                </div>

                                <hr>

                                <?php
                                $valid_until_date = DateTime::createFromFormat('n/j/Y', $valid_until);

                                // Get the current date
                                $current_date = new DateTime();

                                // Compare the two dates
                                if ($valid_until_date < $current_date) {
                                    $status_text = 'Expired';
                                    $status = 'expired-card';
                                } else {
                                    $status_text = 'Active';
                                    $status = 'active-card';
                                }
                                ?>

                                <div class="row-center">
                                    <div class="library-card-status <?php echo $status; ?>"><?php echo $status_text; ?></div>
                                </div>

                                <div class="row-center">

                                    <div class="container-library-id">

                                        <div class="row row-between">

                                            <div class="id-logo-image">
                                                <img src="../images/makaticity-logo.png" alt="" class="image">
                                            </div>

                                            <div class="container-id-header">
                                                <div class="font-size-16" style="font-weight: bold">MAKATI CITY LIBRARY</div>
                                                <div class="font-size-16">8th Floor, Makati City Hall Bldg 1</div>
                                                <div class="font-size-14">J.P. Rizal St., Poblacion, Makati City, Tel. No. 8899-9071</div>
                                            </div>

                                            <div class="id-logo-image">
                                                <img src="../images/library-logo.png" alt="" class="image">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="container-left-id">

                                                <div class="row row-right font-size-16 id" style="font-weight: bold">I.D. No.: <?php echo htmlspecialchars($card_id); ?></div>

                                                <table class="table-id">
                                                    <tr class="tr-id">
                                                        <td class="td-id-none">Name:</td>
                                                        <td class="td-id-bottom name"><?php echo htmlspecialchars($patron_firstname); ?> <?php echo htmlspecialchars($patron_middlename); ?> <?php echo htmlspecialchars($patron_lastname); ?> <?php echo htmlspecialchars($patron_suffix); ?> </td>
                                                    </tr>
                                                    <tr class="tr-id">
                                                        <td class="td-id-none">Home Address:</td>
                                                        <td class="td-id-bottom address"><?php echo htmlspecialchars($patron_house_num); ?> <?php echo htmlspecialchars($patron_street); ?> <?php echo htmlspecialchars($patron_barangay); ?> </td>
                                                    </tr>
                                                    <tr class="tr-id">
                                                        <td class="td-id-none">School/Company:</td>
                                                        <td class="td-id-bottom company"><?php echo htmlspecialchars($patron_company_address); ?></td>
                                                    </tr>
                                                </table>

                                                <div class="font-size-12">
                                                    Present this card each time you borrow any reading or libarary materials. You are responsible for library materials borrowed on this card.
                                                </div>

                                            </div>

                                            <div class="container-right-id">
                                                <div class="id-picture-image">
                                                    <img src="../patron_images/default_image.png" alt="" class="image" id="patronImage">
                                                </div>
                                                <div class="row-center font-size-10" style="font-weight: bold" id="validUntil"> Valid Until: <?php echo htmlspecialchars($valid_until); ?></div>
                                            </div>

                                        </div>

                                        <div class="row row-between">

                                            <div class="id-bottom-row">
                                                <div class="font-size-16">
                                                    Approved by:
                                                </div>

                                                <div>
                                                    <div class="id-librarian-name">
                                                        JENNIFER J. LALUNA
                                                    </div>
                                                    <div class="id-librarian-title">
                                                        Library Division Head
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="id-borrower-sign">
                                                    s
                                                </div>
                                                <div class="id-borrower-label">
                                                    Borrower's Signature
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