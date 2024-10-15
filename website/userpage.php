<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Userpage</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php include '../connection.php'; ?>


<?php

session_start();

$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';


include 'functions/fetch_books.php';
?>



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


            <div class="container-content">

                <div class="contents-big-padding" id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
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

                    <div class="container-info" id="container-info" style="display: <?php echo isset($_SESSION['success_info']) ? $_SESSION['success_info'] : 'none';
                                                                                    unset($_SESSION['success_info']); ?>;">
                        <div>
                            <div class="container-info-title">
                                Note:
                            </div>

                            <div class="container-info-description">
                                Borrowing is permitted for a maximum of 5 days.
                            </div>

                            <div class="container-info-description">
                                Exceeding this period will result in being marked as a delinquent borrower.
                            </div>

                            <div class="container-info-description">
                                In the event of losing the book, it must be replaced in the same condition as when it was borrowed.
                            </div>

                        </div>

                    </div>
                </div>


                <div class="row row-between">

                    <div class="contents-title">
                        Homepage
                    </div>



                    <form action="results_search.php" method="GET" class="container-search row">
                        <input type="text" class="search" id="search" name="query" placeholder="Search by title or author">

                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image" onclick="document.querySelector('form').submit();">
                            </div>
                        </div>
                    </form>


                    <script>
                        function searchBooks() {
                            const query = document.getElementById('search').value;

                            if (query) {
                                window.location.href = `results_search.php?query=${encodeURIComponent(query)}`;
                            }
                        }
                    </script>


                </div>

                <div class="container-content">



                    <!-- rating behaviour -->


                    <?php
                    $pythonScript = 'ratings_cf_svd.py';

                    // Get the book IDs for collaborative filtering (CF)
                    $book_ids_json = shell_exec("py $pythonScript " . $patrons_id);

                    // Decode the JSON output
                    $book_ids = json_decode($book_ids_json, true);

                    // Initialize an empty array for books
                    $books_rating_cf = [];

                    if ($book_ids && count($book_ids) > 0) {
                        // Create a comma-separated string from the book IDs
                        $book_ids_str = implode(',', array_map('intval', $book_ids)); // Ensure book_ids are integers

                        // Adjust the SQL query to use the FIELD() function to follow the order of book IDs
                        $sql = "
                                SELECT 
                                    b.book_id, 
                                    b.title, 
                                    a.author, 
                                    c.category, 
                                    b.image,
                                    IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating, 
                                    br.status AS borrow_status, 
                                    f.status AS favorite_status, 
                                    pr.ratings AS patron_rating
                                FROM 
                                    books b
                                LEFT JOIN 
                                    author a ON b.author_id = a.author_id
                                LEFT JOIN 
                                    category c ON b.category_id = c.category_id
                                LEFT JOIN 
                                    ratings r ON b.book_id = r.book_id
                                LEFT JOIN 
                                    borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id
                                LEFT JOIN 
                                    favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id
                                LEFT JOIN 
                                    ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id
                                WHERE 
                                    b.book_id IN ($book_ids_str)
                                GROUP BY 
                                    b.book_id
                                ORDER BY 
                                    FIELD(b.book_id, $book_ids_str)  -- This ensures the order matches the JSON output
                            ";

                        // Preparing the statement
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);

                        // Execute the statement
                        $stmt->execute();

                        // Fetch all the results
                        $books_rating_cf = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>




                    <div class="contents-big-padding">
                        <div class="row row-between">
                            <div>Based on your rating behaviour</div>
                            <div class="button button-view-more" data-category="Category 1">View More</div>
                        </div>
                        <div class="row-books-container">
                            <div class="arrow-left">
                                <div class="arrow-image">
                                    <img src="../images/prev-black.png" alt="" class="image">
                                </div>
                            </div>
                            <div class="row-books">
                                <?php if ($books_rating_cf && count($books_rating_cf) > 0): ?>
                                    <?php foreach ($books_rating_cf as $book): ?>
                                        <div class="container-books">
                                            <div class="books-id" style="display: none;">
                                                <?php echo htmlspecialchars($book['book_id']); ?>
                                            </div>

                                            <div class="books-image">
                                                <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" alt="Book Image">
                                            </div>

                                            <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category']); ?></div>
                                            <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                                            <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                            <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                            <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['patron_rating']); ?></div>

                                            <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                            <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No recommendations found.</p>
                                <?php endif; ?>
                            </div>
                            <div class="arrow-right">
                                <div class="arrow-image">
                                    <img src="../images/next-black.png" alt="" class="image">
                                </div>
                            </div>
                        </div>
                    </div>






                    <!-- latest rated book -->

                    <?php

                    $pythonScript = 'ratings_cbf_tfidf.py';

                    // Get the book IDs for content-based filtering (CBF)
                    $book_cbf_id_json = shell_exec("py $pythonScript " . $patrons_id);

                    // Decode the JSON output
                    $book_cbf_id = json_decode($book_cbf_id_json, true);

                    // Initialize an empty array for books
                    $books_rating_cbf = [];

                    if ($book_cbf_id && count($book_cbf_id) > 0) {
                        // Create a comma-separated string from the book IDs
                        $book_ids_str = implode(',', array_map('intval', $book_cbf_id)); // Ensure book_ids are integers

                        // Adjust the SQL query to use the FIELD() function to follow the order of book IDs
                        $sql = "
                            SELECT 
                                b.book_id, 
                                b.title, 
                                a.author, 
                                c.category, 
                                b.image,
                                IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating, 
                                br.status AS borrow_status, 
                                f.status AS favorite_status, 
                                pr.ratings AS patron_rating
                            FROM 
                                books b
                            LEFT JOIN 
                                author a ON b.author_id = a.author_id
                            LEFT JOIN 
                                category c ON b.category_id = c.category_id
                            LEFT JOIN 
                                ratings r ON b.book_id = r.book_id
                            LEFT JOIN 
                                borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id
                            LEFT JOIN 
                                favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id
                            LEFT JOIN 
                                ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id
                            WHERE 
                                b.book_id IN ($book_ids_str)
                            GROUP BY 
                                b.book_id
                            ORDER BY 
                                FIELD(b.book_id, $book_ids_str)  -- This ensures the order matches the JSON output
                        ";

                        // Preparing the statement
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);

                        // Execute the statement
                        $stmt->execute();

                        // Fetch all the results
                        $books_rating_cbf = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>





                    <div class="contents-big-padding">
                        <div class="row row-between">
                            <div>Based on your latest rated book</div>
                            <div class="button button-view-more" data-category="Category 1">View More</div>
                        </div>
                        <div class="row-books-container">
                            <div class="arrow-left">
                                <div class="arrow-image">
                                    <img src="../images/prev-black.png" alt="" class="image">
                                </div>
                            </div>
                            <div class="row-books">
                                <?php if ($books_rating_cbf && count($books_rating_cbf) > 0): ?>
                                    <?php foreach ($books_rating_cbf as $book_rating): ?>
                                        <div class="container-books">
                                            <div class="books-id" style="display: none;">
                                                <?php echo htmlspecialchars($book_rating['book_id']); ?>
                                            </div>

                                            <div class="books-image">
                                                <img src="../book_images/<?php echo htmlspecialchars($book_rating['image']); ?>" class="image" alt="Book Image">
                                            </div>

                                            <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book_rating['category']); ?></div>
                                            <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book_rating['borrow_status']); ?></div>
                                            <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book_rating['favorite_status']); ?></div>
                                            <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book_rating['avg_rating']); ?></div>
                                            <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book_rating['patron_rating']); ?></div>

                                            <div class="books-name"><?php echo htmlspecialchars($book_rating['title']); ?></div>
                                            <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book_rating['author']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No recommendations found.</p>
                                <?php endif; ?>
                            </div>
                            <div class="arrow-right">
                                <div class="arrow-image">
                                    <img src="../images/next-black.png" alt="" class="image">
                                </div>
                            </div>
                        </div>
                    </div>







                    <?php
                    $pythonScript = 'borrow_cf_svd.py';

                    $book_ids_json = shell_exec("py $pythonScript " . $patrons_id);

                    // Decode the JSON output
                    $book_ids = json_decode($book_ids_json, true);

                    // Initialize an empty array for books
                    $books_borrow_cf = [];

                    if ($book_ids && count($book_ids) > 0) {
                        // Create a comma-separated string from the book IDs
                        $book_ids_str = implode(',', array_map('intval', $book_ids)); // Ensure book_ids are integers

                        // Adjust the SQL query to use the FIELD() function to follow the order of book IDs
                        $sql = "
                                SELECT 
                                    b.book_id, 
                                    b.title, 
                                    a.author, 
                                    c.category, 
                                    b.image,
                                    IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating, 
                                    br.status AS borrow_status, 
                                    f.status AS favorite_status, 
                                    pr.ratings AS patron_rating
                                FROM 
                                    books b
                                LEFT JOIN 
                                    author a ON b.author_id = a.author_id
                                LEFT JOIN 
                                    category c ON b.category_id = c.category_id
                                LEFT JOIN 
                                    ratings r ON b.book_id = r.book_id
                                LEFT JOIN 
                                    borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id
                                LEFT JOIN 
                                    favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id
                                LEFT JOIN 
                                    ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id
                                WHERE 
                                    b.book_id IN ($book_ids_str)
                                GROUP BY 
                                    b.book_id
                                ORDER BY 
                                    FIELD(b.book_id, $book_ids_str)  -- This ensures the order matches the JSON output
                            ";

                        // Preparing the statement
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);

                        // Execute the statement
                        $stmt->execute();

                        // Fetch all the results
                        $books_borrow_cf = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>



                    <div class="contents-big-padding">
                        <div class="row row-between">
                            <div>Based on user's borrowing habbits</div>
                            <div class="button button-view-more" data-category="Category 1">View More</div>
                        </div>
                        <div class="row-books-container">
                            <div class="arrow-left">
                                <div class="arrow-image">
                                    <img src="../images/prev-black.png" alt="" class="image">
                                </div>
                            </div>
                            <div class="row-books">
                                <?php if ($books_borrow_cf && count($books_borrow_cf) > 0): ?>
                                    <?php foreach ($books_borrow_cf as $borrow_book): ?>
                                        <div class="container-books">
                                            <div class="books-id" style="display: none;">
                                                <?php echo htmlspecialchars($borrow_book['book_id']); ?>
                                            </div>

                                            <div class="books-image">
                                                <img src="../book_images/<?php echo htmlspecialchars($borrow_book['image']); ?>" class="image" alt="Book Image">
                                            </div>

                                            <div class="books-category" style="display: none;"><?php echo htmlspecialchars($borrow_book['category']); ?></div>
                                            <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($borrow_book['borrow_status']); ?></div>
                                            <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($borrow_book['favorite_status']); ?></div>
                                            <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($borrow_book['avg_rating']); ?></div>
                                            <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($borrow_book['patron_rating']); ?></div>

                                            <div class="books-name"><?php echo htmlspecialchars($borrow_book['title']); ?></div>
                                            <div class="books-author" style="display: none;"><?php echo htmlspecialchars($borrow_book['author']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No recommendations found.</p>
                                <?php endif; ?>
                            </div>
                            <div class="arrow-right">
                                <div class="arrow-image">
                                    <img src="../images/next-black.png" alt="" class="image">
                                </div>
                            </div>
                        </div>
                    </div>










                    <!-- categories -->
                    <?php foreach ($books as $category => $bookDetails): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div><?php echo htmlspecialchars($bookDetails[0]['category_name']); ?></div>
                                <div class="button button-view-more" data-category="<?php echo htmlspecialchars($bookDetails[0]['category_name']); ?>">View More</div>
                            </div>
                            <div class="row-books-container">
                                <div class="arrow-left">
                                    <div class="arrow-image">
                                        <img src="../images/prev-black.png" alt="" class="image">
                                    </div>
                                </div>
                                <div class="row-books">
                                    <?php foreach ($bookDetails as $book): ?>
                                        <div class="container-books">
                                            <div class="books-id" style="display: none;"><?php echo htmlspecialchars($book['book_id']); ?></div>

                                            <div class="books-image">
                                                <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image">
                                            </div>

                                            <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                            <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                                            <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                            <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                            <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['patron_rating']); ?></div>

                                            <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                            <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author']); ?></div>

                                        </div>

                                        <!-- Hidden form for borrowing books -->
                                        <form id="borrowForm" action="functions/borrow_books.php" method="POST" style="display: none;">
                                            <input type="hidden" name="book_id" id="bookIdInput">
                                            <input type="hidden" name="patrons_id" id="patronIdInput">
                                            <input type="hidden" name="borrow_status" value="Pending">
                                            <input type="hidden" name="borrow_date" value="">
                                            <input type="hidden" name="return_date" value="">
                                            <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                        </form>


                                        <!-- Hidden form for add favorite books -->
                                        <form id="addFavoriteForm" action="functions/add_favorite.php" method="POST" style="display: none;">
                                            <input type="hidden" name="add_book_id" id="addBookIdInput">
                                            <input type="hidden" name="add_patrons_id" id="addPatronIdInput">
                                            <input type="hidden" name="status" id="statusInput" value="Added">
                                            <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                        </form>

                                        <!-- Hidden form for remove favorite books -->
                                        <form id="removeFavoriteForm" action="functions/remove_favorite.php" method="POST" style="display: none;">
                                            <input type="hidden" name="remove_book_id" id="removeBookIdInput">
                                            <input type="hidden" name="remove_patrons_id" id="removePatronIdInput">
                                            <input type="hidden" name="status" id="statusInput" value="Remove">
                                            <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                        </form>


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

            </div>



            <div class="row-books-contents" id="book-details" style="display: none;">
                <div class="container-books-contents">

                    <div class="books-contents-id" style="display: none;">ID</div>

                    <div class="books-contents-image">Image</div>
                    <div class="books-contents">

                        <div class="row row-between">

                            <div class="books-contents-category" style="display:none;"></div>
                            <div class="books-contents-borrow-status" style="display:none;"></div>
                            <div class="books-contents-favorite" style="display:none;"></div>

                            <div class="books-contents-name">Book Sample</div>
                            <div class="button button-close">&times;</div>

                        </div>

                        <div class="books-contents-author">Book Author</div>

                        <div class="books-contents-ratings" style="display: none;"></div>
                        <div class="books-contents-user-ratings" style="display: none;"></div>


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

                        <div class="row">
                            <div class="tooltipss">
                                <button class="button button-borrow" onmouseover='showTooltip(this)' onmouseout='hideTooltip(this)'>BORROW</button>
                                <span class='tooltiptexts'>Only books from the Circulation Section can be borrowed, but you can still read this book in the library.</span>
                            </div>

                            <div class="tooltipss" id="tooltip-add">
                                <button class="button button-bookmark"><img src="../images/bookmark-white.png" alt=""></button>
                                <span class='tooltiptexts'>Add to favorites</span>
                            </div>


                            <div class="tooltipss" id="tooltip-remove">
                                <button class="button button-bookmark-red"><img src="../images/bookmark-white.png" alt=""></button>
                                <span class='tooltiptexts'>Remove to favorites</span>
                            </div>


                            <div class="tooltipss" id="tooltip-add-ratings">
                                <div class="button button-ratings" onclick="openRateModal()"><img src="../images/star-white.png" alt=""></div>
                                <span class='tooltiptexts'>Add ratings</span>
                            </div>

                            <div class="tooltipss" id="tooltip-update-ratings">
                                <button class="button button-ratings-yellow" onclick="openRateModal()"><img src="../images/star-white.png" alt=""></button>
                                <span class='tooltiptexts'>Update ratings</span>
                            </div>
                        </div>


                        <?php include 'modal/add_rating_modal.php'; ?>


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



<script src="js/sidebar.js"></script>
<script src="js/book-scroll.js"></script>
<script src="js/close-status.js"></script>
<script src="js/tooltips.js"></script>




<!-- borrow submit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const borrowButton = document.querySelector('.button-borrow');

        if (borrowButton) {
            borrowButton.addEventListener('click', function() {
                // Get the book ID from the DOM
                const bookId = document.querySelector('.books-contents-id').textContent.trim();

                // Get the user ID from PHP (passed into the script)
                const patronId = <?php echo json_encode($patrons_id); ?>;

                if (bookId && patronId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('bookIdInput').value = bookId;
                    document.getElementById('patronIdInput').value = patronId;

                    // Submit the form
                    document.getElementById('borrowForm').submit();
                }
            });
        }
    });
</script>



<!-- add favorites submit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteButton = document.querySelector('.button-bookmark');

        if (favoriteButton) {
            favoriteButton.addEventListener('click', function() {
                // Get the book ID from the DOM
                const addBookId = document.querySelector('.books-contents-id').textContent.trim();

                // Get the user ID from PHP (passed into the script)
                const addPatronId = <?php echo json_encode($patrons_id); ?>;

                if (addBookId && addPatronId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('addBookIdInput').value = addBookId;
                    document.getElementById('addPatronIdInput').value = addPatronId;

                    // Submit the form
                    document.getElementById('addFavoriteForm').submit();
                }
            });
        }
    });
</script>

<!-- remove favorites submit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteButton = document.querySelector('.button-bookmark-red');

        if (favoriteButton) {
            favoriteButton.addEventListener('click', function() {
                // Get the book ID from the DOM
                const removeBookId = document.querySelector('.books-contents-id').textContent.trim();

                // Get the user ID from PHP (passed into the script)
                const removePatronId = <?php echo json_encode($patrons_id); ?>;

                if (removeBookId && removePatronId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('removeBookIdInput').value = removeBookId;
                    document.getElementById('removePatronIdInput').value = removePatronId;

                    // Submit the form
                    document.getElementById('removeFavoriteForm').submit();
                }
            });
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.button-view-more').forEach(function(button) {
            button.addEventListener('click', function() {
                var category = this.getAttribute('data-category');
                var encodedCategory = encodeURIComponent(category);
                window.location.href = 'results.php?category=' + encodedCategory;
            });
        });
    });
</script>