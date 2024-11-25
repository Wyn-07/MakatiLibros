<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Homepage</title>

    <link rel="stylesheet" href="website/style.css">

    <link rel="website icon" href="images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php include 'connection.php'; ?>

<body>
    <div class="wrapper">

        <div class="container-top">
            <div class="row row-between-top">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="images/library-logo.png" class="image">
                    </div>
                    Makati City Hall Library <a href="admin/login.php" style="color:#393E46">a</a>
                </div>


                <div class="container-navigation">

                    <a href="website/homepage.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="website/login.php" class="navigation-contents">LOG IN</a>

                    <a href="website/signup.php" class="navigation-contents">SIGN UP</a>

                </div>

            </div>
        </div>


        <div class="container-banner">
            <div class="banner-image">
                <img src="images/image1.jpg" class="image" alt="Image 1">
                <img src="images/image2.png" class="image" alt="Image 2">
                <img src="images/image3.jfif" class="image" alt="Image 3">
            </div>
            <div class="image-slider">
                <button class="prev" onclick="prevSlide()">&#10094;</button>
                <button class="next" onclick="nextSlide()">&#10095;</button>
            </div>
        </div>


        <div class="row row-between title-search">

            <div class="contents-title">
                Homepage
            </div>


            <!-- loading animation -->
            <div id="loading-overlay">
                <div class="spinner"></div>
            </div>


            <!-- search field -->
            <form action="website/results_search_homepage.php" method="GET" class="container-search row">
                <input type="text" class="search" id="search" name="query" autocomplete="off" placeholder="Search by title">

                <div class="container-search-image">
                    <div class="search-image">
                        <img src="images/search-black.png" class="image" onclick="document.querySelector('form').submit();">
                    </div>
                </div>
            </form>


        </div>



        <div class="row-body">


            <div class="container-content">

                <?php include 'website/functions/fetch_books_limit.php'; ?>

                <?php foreach ($books_limit as $category => $bookDetails): ?>
                    <div class="contents-big-padding">
                        <div class="row row-between">
                            <div><?php echo htmlspecialchars($bookDetails[0]['category_name']); ?></div>
                            <div class="button button-view-more" data-category="<?php echo htmlspecialchars($bookDetails[0]['category_name']); ?>">View More</div>
                        </div>
                        <div class="row-books-container">
                            <div class="arrow-left">
                                <div class="arrow-image">
                                    <img src="images/prev-black.png" alt="" class="image">
                                </div>
                            </div>
                            <div class="row-books">
                                <?php foreach ($bookDetails as $book): ?>

                                    <div class="container-books">

                                        <div class="books-id" style="display: none;"><?php echo htmlspecialchars($book['book_id']); ?></div>

                                        <?php
                                        // Check conditions for displaying Non-circulating
                                        if ($book['book_status'] === 'Available' && $book['category_name'] !== 'Circulation') {
                                            $statusCategoryText = "Non-circulating";
                                            $statusCategoryClass = "unavailable";
                                            $hideStatus = false;
                                        } else {
                                            $statusCategoryText = htmlspecialchars($book['book_status']);
                                            $statusCategoryClass = ($book['book_status'] === 'Available') ? 'available' : 'unavailable';
                                            $hideStatus = true;
                                        }
                                        ?>

                                        <div class="books-image">
                                            <div class="books-status-show <?php echo $statusCategoryClass; ?>" <?php echo $hideStatus ? 'style="display: none;"' : ''; ?>>
                                                <?php echo htmlspecialchars($book['book_status']); ?>
                                            </div>

                                            <div class="books-status-category <?php echo $statusCategoryClass; ?>">
                                                <?php echo $statusCategoryText; ?>
                                            </div>

                                            <img src="book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" loading="lazy">
                                        </div>

                                        <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>
                                        <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                        <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                        <div class="books-copyright" style="display: none;"><?php echo htmlspecialchars($book['copyright']); ?></div>

                                        <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                        <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author']); ?></div>

                                    </div>



                                <?php endforeach; ?>
                            </div>
                            <div class="arrow-right">
                                <div class="arrow-image">
                                    <img src="images/next-black.png" alt="" class="image">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


            </div>


            <!-- display books when click -->

            <div class="row-books-contents-modal-parent" id="book-details" style="display: none;">

                <div class="row-books-contents-modal">

                    <div class="container-books-contents-modal">

                        <div class="books-contents-id" style="display: none;">ID</div>

                        <div class="books-contents-image">Image</div>

                        <div class="books-contents">

                            <div class="row row-between">

                                <div class="books-contents-category"></div>

                                <div class="button button-close">&times;</div>

                            </div>

                            <div class="books-contents-name">Book Sample</div>

                            <div class="row">
                                <div class="books-contents-author">Book Author</div>
                                <div class="books-contents-copyright">0000</div>
                            </div>

                            <div class="books-contents-ratings" style="display: none;"></div>


                            <div class="row">
                                <div class="star-rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>

                                <div class="ratings-description">
                                    <div class="ratings-number"> </div>&nbspout of 5
                                </div>
                            </div>




                        </div>
                    </div>


                </div>

                <script src="website/js/book-details-toggle-homepage.js"></script>

            </div>

        </div>


        <div class="container-footer">

            <div class="transparent">

                <div class="row row-footer">

                    <div class="container-footer-left">

                        <div class="container-footer-left-image">
                            <img src="images/library-logo.png" class="image">
                        </div>

                        <div class="footer-image-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </div>
                    </div>


                    <div class="container-footer-right">

                        <div class="footer-contents">
                            <div class="footer-title">
                                Library Location
                            </div>
                            <div class="footer-description">
                                8FL Makati City Hall
                            </div>
                        </div>

                        <div class="footer-contents">
                            <div class="footer-title">
                                Email Address:
                            </div>
                            <div class="footer-description">
                                makaticityhall@gmail.com
                            </div>
                        </div>

                        <div class="footer-contents">
                            <div class="footer-title">
                                Contact Number:
                            </div>
                            <div class="footer-description">
                                09957733887
                            </div>
                        </div>


                        <div class="footer-contents">
                            <div class="footer-title">
                                Telephone Number:
                            </div>
                            <div class="footer-description">
                                8123-4567
                            </div>
                        </div>


                        <div class="footer-contents">
                            <div class="footer-title">
                                Facebook:
                            </div>
                            <div class="footer-description">
                                <a href="https://www.facebook.com/MyMakatiVerified/" class="footer-link">
                                    https://www.facebook.com/MyMakatiVerified/
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</body>



</html>


<script src="website/js/banner.js"></script>
<script src="website/js/book-scroll.js"></script>
<script src="website/js/loading-animation.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.button-view-more').forEach(function(button) {
            button.addEventListener('click', function() {
                var category = this.getAttribute('data-category');
                var encodedCategory = encodeURIComponent(category);
                window.location.href = 'website/results_more_homepage.php?category=' + encodedCategory;
            });
        });
    });
</script>


<script>
    document.querySelectorAll('.rate label').forEach(label => {
        label.addEventListener('click', (e) => {
            e.preventDefault();
        });
    });
</script>