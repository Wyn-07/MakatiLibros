<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php include '../connection.php'; ?>

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


        <div class="container-banner">
            <div class="banner-image">
                <img src="../images/image1.jpg" class="image" alt="Image 1">
                <img src="../images/image2.png" class="image" alt="Image 2">
                <img src="../images/image3.jfif" class="image" alt="Image 3">
            </div>
            <div class="image-slider">
                <button class="prev" onclick="prevSlide()">&#10094;</button>
                <button class="next" onclick="nextSlide()">&#10095;</button>
            </div>
        </div>



        <div class="row-body">


            <div class="container-content" >

                <?php include 'functions/fetch_books.php'; ?>

                <?php foreach ($books as $category => $bookDetails): ?>
                    <div class="contents-big-padding">
                        <div class="row row-between">
                            <div><?php echo htmlspecialchars($category); ?></div>
                            <div class="button button-view-more" onclick="goToLogin()">View More</div>
                        </div>
                        <div class="row-books-container">
                            <div class="arrow-left">
                                <div class="arrow-image">
                                    <img src="../images/prev-black.png" alt="" class="image">
                                </div>
                            </div>
                            <div class="row-books">
                                <?php foreach ($bookDetails as $book): ?>
                                    <div class="container-books" id="book-<?php echo htmlspecialchars($book['book_id']); ?>">
                                        <div class="books-image">
                                            <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image">
                                        </div>
                                        <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                        <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['authors']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="arrow-right">
                                <div class="arrow-image">
                                    <img src="../images/next-black.png" alt="" class="image">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


            </div>


            <div class="row-books-contents" id="book-details" style="display: none;">
                <div class="container-books-contents">
                    <div class="books-contents-image">Image</div>
                    <div class="books-contents">

                        <div class="row row-between">
                            <div class="books-contents-name">Book Sample</div>
                            <div class="button button-close">&times;</div>
                        </div>

                        <div class="books-contents-author">Book Author</div>


                        <div class="rate" onclick="goToLogin()">
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1" title="text">1 star</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                        </div>


                        <div class="row">
                            <div class="button button-borrow"  onclick="goToLogin()">BORROW</div>
                            <div class="button button-bookmark"  onclick="goToLogin()"><img src="../images/bookmark-white.png" alt=""></div>
                        </div>

                        
                    </div>
                </div>

                <script src="js/book-details-toggle.js"></script>
            </div>


        </div>


        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>

    </div>
</body>



</html>


<script src="js/banner.js"></script>
<script src="js/book-details-toggle.js"></script>
<script src="js/book-scroll.js"></script>

<script>
    function goToLogin() {
        window.location.href = 'login.php';
    }
</script>

<script>
    document.querySelectorAll('.rate label').forEach(label => {
    label.addEventListener('click', (e) => {
        e.preventDefault();
    });
});
</script>