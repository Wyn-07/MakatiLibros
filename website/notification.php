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




                        <div id="myTransaction" style="width: 100%;">

                            <div class="profile-container-search">
                                <div class="profile-container-search-image">
                                    <div class="search-image">
                                        <img src="../images/search-black.png" class="image">
                                    </div>
                                </div>
                                <input type="text" id="search" class="search" placeholder="Search here..." autocomplete="off">
                            </div>
                        
                           <div class="profile-container-white-filter-content">

                                <div class="row">
                                   <div class="container-notif-image">
                                      <img src="" class="images" alt="">
                                   </div>
                                   <div>
                                        <div class="notification-title">You have successfully borrowed the book</div>
                                        <div class="notification-description"></div>
                                   </div>
            
                                </div>
                                
                           </div>


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



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const profileContainers = document.querySelectorAll('.profile-container-white-filter-content');
        const noResultsContainer = document.querySelector('#no-results-container'); 

        // Event listener for search input
        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();
            let resultsFound = false; // Flag to check if any result is found

            profileContainers.forEach(function(container) {
                // Get the values from the container
                const title = container.querySelector('.books-contents-name').textContent.toLowerCase();
                const author = container.querySelector('.books-contents-author').textContent.toLowerCase();
                const copyright = container.querySelector('.books-contents-copyright').textContent.toLowerCase();

                // Get individual date, time, and dayOfWeek elements (original)
                const date = container.querySelector('.formatted-date') ? container.querySelector('.formatted-date').textContent.toLowerCase() : '';
                const time = container.querySelector('.formatted-time') ? container.querySelector('.formatted-time').textContent.toLowerCase() : '';
                const dayOfWeek = container.querySelector('.day-of-week') ? container.querySelector('.day-of-week').textContent.toLowerCase() : '';

                // Get individual return date, time, and dayOfWeek elements (return)
                const dateReturn = container.querySelector('.formatted-date-return') ? container.querySelector('.formatted-date-return').textContent.toLowerCase() : '';
                const timeReturn = container.querySelector('.formatted-time-return') ? container.querySelector('.formatted-time-return').textContent.toLowerCase() : '';
                const dayOfWeekReturn = container.querySelector('.day-of-week-return') ? container.querySelector('.day-of-week-return').textContent.toLowerCase() : '';

                // Check if any of the values match the search term
                if (title.includes(searchTerm) ||
                    author.includes(searchTerm) ||
                    copyright.includes(searchTerm) ||
                    date.includes(searchTerm) ||
                    time.includes(searchTerm) ||
                    dayOfWeek.includes(searchTerm) ||
                    dateReturn.includes(searchTerm) ||
                    timeReturn.includes(searchTerm) ||
                    dayOfWeekReturn.includes(searchTerm)) {
                    container.style.display = 'flex'; // Show the container if it matches
                    resultsFound = true; // Set flag to true if any result is found
                } else {
                    container.style.display = 'none'; // Hide the container if it doesn't match
                }
            });

            // If no results found, show the "No Results" message
            if (!resultsFound) {
                noResultsContainer.style.display = 'flex'; 
            } else {
                noResultsContainer.style.display = 'none'; 
            }
        });
    });
</script>