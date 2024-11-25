<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Results</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

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

                <div class="row row-between title-search">
                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($title); ?>"
                    </div>
                </div>


                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
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

                            <div class="books-contents-category" style="display:none"><?php echo htmlspecialchars($category_name); ?></div>
                            <div class="books-contents-borrow-status" style="display:none"><?php echo htmlspecialchars($borrow_status); ?></div>
                            <div class="books-contents-favorite" style="display:none"><?php echo htmlspecialchars($favorite_status); ?></div>
                            <div class="books-contents-user-ratings" style="display:none"><?php echo htmlspecialchars($patron_rating); ?></div>

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
                                    <button class="button button-borrow" onmouseover='showTooltip(this)' onmouseout='hideTooltip(this)'>BORROW</button>
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
                                        } else if (bookBorrowStatus.toLowerCase() === 'borrowing') {
                                            borrowButton.disabled = true;
                                            tooltip.textContent = 'You are still borrowing the book. Please return it on time.';
                                        } else {
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














                <!-- Book Similar -->
                <div class="container-content">

                    <div class="result-contents">

                        <div class="contents-title">
                            Similar Books
                        </div>


                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $book_id = $_POST['book_id'] ?? '';
                        }

                        // Define the path to your Python script
                        $pythonScript = 'search_cbf_tfidf.py';

                        // Execute the Python script with the book_id argument and capture the output
                        $book_cbf_id_json = shell_exec("py ../python_algorithm/$pythonScript " . escapeshellarg($book_id));

                        // Decode the JSON output from the Python script
                        $book_cbf_id = json_decode($book_cbf_id_json, true);

                        // Initialize an empty array to store book results
                        $books_search_cbf = [];

                        if ($book_cbf_id && count($book_cbf_id) > 0) {
                            // Create named placeholders for each book ID in the array
                            $placeholders = [];
                            foreach ($book_cbf_id as $index => $id) {
                                $placeholders[] = ":book_id_{$index}";
                            }
                            $placeholders_str = implode(',', $placeholders);

                            // Prepare the SQL query using named placeholders for book IDs
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
                                        b.book_id IN ($placeholders_str)
                                    GROUP BY 
                                        b.book_id
                                    ORDER BY 
                                        FIELD(b.book_id, $placeholders_str)
                                ";

                            // Prepare the SQL statement
                            $stmt = $pdo->prepare($sql);

                            // Bind the patron's ID to the query
                            $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);

                            // Bind the book IDs dynamically using the named placeholders
                            foreach ($book_cbf_id as $index => $book_id) {
                                $stmt->bindValue(":book_id_{$index}", $book_id, PDO::PARAM_INT);
                            }

                            // Execute the statement
                            $stmt->execute();

                            // Fetch the result as an associative array
                            $books_search_cbf = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        ?>




                        <div>

                            <div class="row-contents-center" id="bookContainer">
                                <?php if (!empty($books_search_cbf)): ?>
                                    <?php foreach ($books_search_cbf as $row): ?>
                                        <div class="container-books-2" id="book-<?php echo htmlspecialchars($row['book_id']); ?>">
                                            <div class="books-image-2">
                                                <img src="../book_images/<?= htmlspecialchars($row['image']) ?>" class="image">
                                            </div>
                                            <div class="books-name-2">
                                                <?php echo htmlspecialchars($row['title']); ?>
                                            </div>
                                            <div class="books-author" style="display:none">
                                                <?php echo htmlspecialchars($row['author']); ?>
                                            </div>
                                            <div class="books-categories" style="display:none">
                                                <?php echo htmlspecialchars($row['category']); ?>
                                            </div>
                                            <div class="books-copyright" style="display:none">
                                                <?php echo htmlspecialchars($row['copyright']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No recommended books found.</p>
                                <?php endif; ?>
                            </div>


                            <div id="not-found-message" class="container-unavailable" style="display: none;">
                                <div class="unavailable-image">
                                    <img src="../images/no-books.png" class="image">
                                </div>
                                <div class="unavailable-text">Not Found</div>
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
                                        <div class="books-contents-ratings" style="display:none"></div>

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
                                            <div class="button button-borrow">BORROW</div>
                                            <div class="button button-bookmark"><img src="../images/bookmark-white.png" alt=""></div>
                                            <div class="button button-ratings" onclick="openRateModal()"><img src="../images/star-white.png" alt=""></div>
                                        </div>

                                        <?php include 'modal/add_rating_modal.php'; ?>

                                    </div>
                                </div>

                                <script src="js/book-details-toggle-2.js"></script>

                            </div>

                            <div class="row row-center">

                                <div class="pagination-controls">
                                    Items per page:
                                    <select class="page-select" id="itemsPerPage">
                                        <option value="20">20</option>
                                        <option value="40">40</option>
                                        <option value="60">60</option>
                                    </select>
                                </div>

                                <div class="pagination-controls">
                                    <button class="button button-page" id="prevPage">Previous</button>
                                    <span class="page-number" id="pageInfo"></span>
                                    <button class="button button-page" id="nextPage">Next</button>
                                </div>

                            </div>

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
<script src="js/loading-animation.js"></script>
<!-- <script src="js/book-list-pagination.js"></script> -->