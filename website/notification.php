<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>



<?php

session_start();

include '../connection.php';

include 'functions/fetch_notification.php';


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
                            Borrow Notifications
                        </div>
                        <div class="profile-subtitle-white">
                            View and manage your notifications.
                        </div>
                        <div class="profile-subtitle-white">
                            Stay informed about your book borrow status.
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
                                            <div id="notification" class="profile-nav-items nav-notification" onclick="toggleSection('myNotification')">Notification</div>
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




                        <div id="myTransaction" style="width: 100%;">


                            <div class="profile-container-white-filter-content">

                                <div class="row row-right">

                                    <form action="functions/update_notification_all.php" method="POST">
                                        <button type="submit" name="mark_all" value="mark_all" class="button button-mark">Read all notifications</button>
                                    </form>

                                </div>

                            </div>



                            <div class="profile-container-search">
                                <div class="profile-container-search-image">
                                    <div class="search-image">
                                        <img src="../images/search-black.png" class="image">
                                    </div>
                                </div>
                                <input type="text" id="search" class="search" placeholder="Search here..." onkeyup="filterNotifications()" autocomplete="off">
                            </div>


                            <?php
                            // Prepare notifications array for sorting
                            usort($notifications, function ($a, $b) {
                                // Determine the date for sorting for each notification
                                $dateA = null;
                                $dateB = null;

                                if ($a['borrow_status'] === 'Accepted') {
                                    $dateA = DateTime::createFromFormat('n/j/Y H:i:s', "{$a['accepted_date']} {$a['accepted_time']}");
                                } elseif ($a['borrow_status'] === 'Borrowed') {
                                    $dateA = DateTime::createFromFormat('n/j/Y H:i:s', "{$a['borrow_date']} {$a['borrow_time']}");
                                } elseif ($a['borrow_status'] === 'Returned') {
                                    $dateA = DateTime::createFromFormat('n/j/Y H:i:s', "{$a['return_date']} {$a['return_time']}");
                                }

                                if ($b['borrow_status'] === 'Accepted') {
                                    $dateB = DateTime::createFromFormat('n/j/Y H:i:s', "{$b['accepted_date']} {$b['accepted_time']}");
                                } elseif ($b['borrow_status'] === 'Borrowed') {
                                    $dateB = DateTime::createFromFormat('n/j/Y H:i:s', "{$b['borrow_date']} {$b['borrow_time']}");
                                } elseif ($b['borrow_status'] === 'Returned') {
                                    $dateB = DateTime::createFromFormat('n/j/Y H:i:s', "{$b['return_date']} {$b['return_time']}");
                                }

                                // Handle cases where dates might be invalid
                                if (!$dateA) return 1;
                                if (!$dateB) return -1;

                                return $dateB <=> $dateA; // Descending order
                            });

                            foreach ($notifications as $notif):
                            ?>
                                <?php
                                // Determine the container class
                                $containerClass = $notif['seen'] === 'Yes' ? 'notif-container-white-opacity' : 'notif-container-white';
                                $buttonText = $notif['seen'] === 'Yes' ? 'Unread' : 'Read';

                                // Initialize variables for the notification content
                                $notifTitle = '';
                                $notifDescription = '';
                                $notifDate = '';

                                // Convert borrow_date to DateTime object
                                $borrowDate = DateTime::createFromFormat('n/j/Y H:i:s', "{$notif['borrow_date']} {$notif['borrow_time']}");

                                // If return_date is 'Pending', calculate a return date 5 days from the borrow_date
                                if ($notif['return_date'] === 'Pending' && $borrowDate) {
                                    $returnDate = $borrowDate->add(new DateInterval('P5D'));
                                    $notifDescription = "You have successfully borrowed the book \"{$notif['book_title']}\". Please return it by {$returnDate->format('n/j/Y')}. Enjoy your reading!";
                                    $notifDate = "{$borrowDate->format('n/j/Y')} {$borrowDate->format('H:i')}";
                                } else {
                                    $returnDate = DateTime::createFromFormat('n/j/Y H:i:s', "{$notif['return_date']} {$notif['return_time']}");
                                }

                                // Set notification content based on borrow status
                                if ($notif['borrow_status'] === 'Accepted') {
                                    $notifTitle = "Your book request has been accepted!";
                                    $notifDescription = "You can now claim the book \"{$notif['book_title']}\" from the library. Thank you!";
                                    $notifDate = "{$notif['accepted_date']} {$notif['accepted_time']}";
                                } elseif ($notif['borrow_status'] === 'Borrowed') {
                                    if ($borrowDate && $returnDate) {
                                        $remainingDays = $returnDate->diff(new DateTime())->days;

                                        if ($remainingDays == 1) {
                                            $notifTitle = "Reminder: Return Book in One Day";
                                            $notifDescription = "Please return the book \"{$notif['book_title']}\" by {$returnDate->format('n/j/Y')} to avoid late fees.";
                                            $notifDate = (new DateTime())->format('n/j/Y H:i');
                                        } else {
                                            $notifTitle = "You have successfully borrowed the book";
                                            $notifDescription = "You have successfully borrowed the book \"{$notif['book_title']}\". Please return it by {$returnDate->format('n/j/Y')}. Enjoy your reading!";
                                            $notifDate = "{$notif['borrow_date']} {$notif['borrow_time']}";
                                        }
                                    } else {
                                        $notifDescription = "Error parsing borrow or return dates.";
                                    }
                                } elseif ($notif['borrow_status'] === 'Returned') {
                                    $notifTitle = "You have successfully returned the book";
                                    $notifDescription = "Thank you for returning the book \"{$notif['book_title']}\". We hope you enjoyed reading it!";
                                    $notifDate = "{$notif['return_date']} {$notif['return_time']}";
                                } elseif ($notif['borrow_status'] === 'delinquent') {
                                    $notifTitle = "Notice: Temporary Ban on Borrowing Privileges";
                                    $notifDescription = "You have been temporarily banned from borrowing books due to not returning the book \"{$notif['book_title']}\" on time. Please return the book to regain borrowing privileges.";
                                    $notifDate = (new DateTime())->format('n/j/Y H:i');
                                }
                                ?>

                                <div class="<?= $containerClass ?>  notif-item">
                                    <div class="row">

                                        <div class="container-notif-image">
                                            <img src="../book_images/<?= $notif['book_image'] ?>" class="image" alt="<?= $notif['book_title'] ?>">
                                        </div>

                                        <div class="notif-contents">
                                            <div class="notification-title"><?= $notifTitle ?></div>
                                            <div class="notification-description"><?= $notifDescription ?></div>
                                            <div class="notification-date"><?= $notifDate ?></div>
                                        </div>

                                        <div>
                                            <form action="functions/update_notification.php" method="POST">
                                                <input type="hidden" name="notif_id" value="<?= $notif['notif_id'] ?>">
                                                <input type="hidden" name="current_seen" value="<?= $notif['seen'] ?>">
                                                <button type="submit" name="submit" class="button button-mark"><?= $buttonText ?></button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            <?php endforeach; ?>

                            <script>
                                function filterNotifications() {
                                    const searchInput = document.getElementById('search').value.toLowerCase();
                                    const notifItems = document.querySelectorAll('.notif-item');
                                    var noResultsContainer = document.getElementById('no-results-container');
                                    var resultsFound = false;


                                    notifItems.forEach(item => {
                                        const title = item.querySelector('.notification-title').textContent.toLowerCase();
                                        const description = item.querySelector('.notification-description').textContent.toLowerCase();
                                        const date = item.querySelector('.notification-date').textContent.toLowerCase();

                                        if (title.includes(searchInput) || description.includes(searchInput) || date.includes(searchInput)) {
                                            item.style.display = '';
                                            resultsFound = true;

                                        } else {
                                            item.style.display = 'none';
                                        }
                                    });

                                    if (!resultsFound) {
                                        noResultsContainer.style.display = 'flex';
                                    } else {
                                        noResultsContainer.style.display = 'none';
                                    }
                                }
                            </script>






                            <div id="no-results-container" style="display: none; min-height: 300px; justify-content: center; align-items: center; flex-direction: column; width: 100%; background-color: white; padding: 20px 40px; margin-bottom: 10px;">
                                <div class="unavailable-image">
                                    <img src="../images/no-books.png" class="image" alt="No Books Available">
                                </div>
                                <div class="unavailable-text">No Results</div>
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