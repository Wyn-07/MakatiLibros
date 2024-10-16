<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Results</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php session_start();

$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;


?>



<?php include '../connection.php'; ?>
<?php include 'functions/fetch_category.php'; ?>

<body>
    <div class="wrapper">


        <div class="container-top">
            <?php include 'navbar.php'; ?>
        </div>



        <div id="overlay" class="overlay"></div>


        <div class="row-body">

            <div class="container-sidebar" id="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>

            <?php
            // Check if form is submitted and retrieve the book details from POST data
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $book_id = $_POST['book_id'] ?? '';
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $image = $_POST['image'] ?? '';
                $avg_rating = $_POST['avg_rating'] ?? 'N/A';
                $borrow_status = $_POST['borrow_status'] ?? '';
                $favorite_status = $_POST['favorite_status'] ?? '';
                $patron_rating = $_POST['patron_rating'] ?? 'N/A';
                $category_name = $_POST['category_name'] ?? 'N/A';
            } else {
                // Handle case when no data is submitted
                $title = "No Book Selected";
                $author = "Unknown";
                $image = "../book_images/default.jpg"; // Default image
            }
            ?>


            <div class="container-content">

                <div class="row row-between">
                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($title); ?>"
                    </div>
                </div>

                <div class="row-center">
                    <div class="container-books-2">
                        <div class="books-image-2">
                            <img src="../book_images/<?php echo htmlspecialchars($image); ?>" class="image" alt="<?php echo htmlspecialchars($title); ?>">
                        </div>
                        <div class="books-name-2">
                            <?php echo htmlspecialchars($title); ?>
                        </div>
                    </div>
                </div>

                <div class="row-books-contents">
                    <div class="container-books-contents">
                        <div class="books-contents-image">
                            <img src="../book_images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="image">
                        </div>
                        <div class="books-contents">
                            <div class="row row-between">
                                <div class="books-contents-name"><?php echo htmlspecialchars($title); ?></div>
                            </div>
                            <div class="books-contents-author"><?php echo htmlspecialchars($author); ?></div>
                            <div class="books-contents-ratings" style="display:none"><?php echo htmlspecialchars($avg_rating); ?></div>

                            <div class="books-contents-category"><?php echo htmlspecialchars($category_name); ?></div>
                            <div class="books-contents-borrow-status"><?php echo htmlspecialchars($borrow_status); ?></div>
                            <div class="books-contents-favorite"><?php echo htmlspecialchars($favorite_status); ?></div>
                            <div class="books-contents-user-ratings"><?php echo htmlspecialchars($patron_rating); ?></div>

                            <div class="row">
                                <div class="star-rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>

                                <div class="ratings-description">
                                    <div class="ratings-number"><?php echo htmlspecialchars($avg_rating); ?></div>&nbspout of 5
                                </div>
                            </div>

                            <div class="row">
                                <div class="tooltipss">
                                    <button class="button button-borrow" onmouseover='showTooltip(this)' onmouseout='hideTooltip(this)'>
                                        BORROW
                                    </button>
                                    <span class='tooltiptexts'></span>
                                </div>

                                <div class="tooltipss" id="tooltip-add">
                                    <button class="button button-bookmark"><img src="../images/bookmark-white.png" alt=""></button>
                                    <span class='tooltiptexts'>Add to favorites</span>
                                </div>

                                <div class="tooltipss" id="tooltip-remove">
                                    <button class="button button-bookmark-red"><img src="../images/bookmark-white.png" alt=""></button>
                                    <span class='tooltiptexts'>Remove from favorites</span>
                                </div>

                                <div class="tooltipss" id="tooltip-add-ratings">
                                    <button class="button button-ratings" onclick="openRateModal('<?php echo htmlspecialchars($book_id); ?>', '<?php echo htmlspecialchars($patron_rating); ?>', <?php echo json_encode($patrons_id); ?>)">
                                        <img src="../images/star-white.png" alt=""></button>
                                    <span class='tooltiptexts'>Add ratings</span>
                                </div>

                                <div class="tooltipss" id="tooltip-update-ratings">
                                    <button class="button button-ratings-yellow" onclick="openRateModal('<?php echo htmlspecialchars($book_id); ?>', '<?php echo htmlspecialchars($patron_rating); ?>', <?php echo json_encode($patrons_id); ?>)">
                                        <img src="../images/star-white.png" alt=""></button>
                                    <span class='tooltiptexts'>Update ratings</span>
                                </div>
                            </div>

                            <?php include 'modal/add_rating_modal_2.php'; ?>


                            <script>
                                // Get the necessary elements from the DOM
                                const bookCategory = document.querySelector('.books-contents-category').textContent.trim();
                                const bookBorrowStatus = document.querySelector('.books-contents-borrow-status').textContent.trim();
                                const bookFavorite = document.querySelector('.books-contents-favorite').textContent.trim();

                                const borrowButton = document.querySelector('.button-borrow');
                                const tooltip = document.querySelector('.tooltiptexts');
                                const favoriteButton = document.querySelector('.button-bookmark');
                                const favoriteButtonRed = document.querySelector('.button-bookmark-red');
                                const ratingButton = document.querySelector('.button-ratings');
                                const ratingButtonYellow = document.querySelector('.button-ratings-yellow');
                                const tooltipAddRatings = document.querySelector('#tooltip-add-ratings');
                                const tooltipUpdateRatings = document.querySelector('#tooltip-update-ratings');



                                // Function to update borrow button and tooltip
                                function updateBorrowButton() {
                                    if (bookCategory.toLowerCase() !== 'circulation') {
                                        borrowButton.disabled = true;
                                        tooltip.textContent = 'Only books from the Circulation Section can be borrowed, but you can still read this book in the library.';
                                    } else {
                                        borrowButton.disabled = false;
                                        if (bookBorrowStatus.toLowerCase() === 'pending') {
                                            borrowButton.disabled = true;
                                            tooltip.textContent = 'You have already requested to borrow this book. You can now claim it at the library.';
                                        } else if (bookBorrowStatus.toLowerCase() === 'borrowed') {
                                            borrowButton.disabled = true;
                                            tooltip.textContent = 'You are still borrowing the book. Please return it on time.';
                                        } else {
                                            borrowButton.textContent = 'Borrow';
                                            tooltip.style.display = 'none';
                                        }
                                    }
                                    tooltip.style.display = 'flex';
                                }

                                // Function to update favorite button visibility
                                function updateFavoriteButton() {
                                    if (bookFavorite) {
                                        favoriteButton.style.display = 'none';
                                        favoriteButtonRed.style.display = 'flex';
                                    } else {
                                        favoriteButton.style.display = 'flex';
                                        favoriteButtonRed.style.display = 'none';
                                    }
                                }

                                // Function to update rating buttons based on user rating
                                function updateRatingButtons() {
                                    const bookUserRating = parseFloat(document.querySelector('.books-contents-user-ratings').textContent.trim()) || 0;
                                    if (bookUserRating > 0) {
                                        ratingButton.style.display = 'none';
                                        ratingButtonYellow.style.display = 'flex';
                                    } else {
                                        ratingButton.style.display = 'flex';
                                        ratingButtonYellow.style.display = 'none';
                                    }
                                }

                                // Initial updates based on the fetched data
                                updateBorrowButton();
                                updateFavoriteButton();
                                updateRatingButtons();

                                // Handle star ratings
                                const stars = document.querySelectorAll('.star');
                                let rating = parseFloat(<?php echo json_encode($patron_rating); ?>) || 0;

                                if (!isNaN(rating)) {
                                    rating = Math.round(rating);
                                    stars.forEach(star => {
                                        const value = parseFloat(star.getAttribute('data-value'));
                                        star.classList.toggle('active', value <= rating);
                                    });
                                }

                                // Add event listeners for star click
                                stars.forEach(star => {
                                    star.addEventListener('click', function() {
                                        const selectedRating = parseFloat(this.getAttribute('data-value'));
                                        stars.forEach(s => s.classList.toggle('active', s.getAttribute('data-value') <= selectedRating));
                                    });
                                });
                            </script>


                        </div>
                    </div>
                </div>































            </div>

        </div>


        <div class="container-footer">
            <?php include 'footer.php'; ?>
        </div>


    </div>
</body>



</html>

<script src="js/sidebar.js"></script>
<!-- <script src="js/book-list-pagination.js"></script> -->