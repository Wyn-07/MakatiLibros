<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Userpage</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php include '../connection.php'; ?>


<?php

session_start();

// Include database connection
include '../connection.php';

$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';


include 'functions/fetch_books_limit.php';

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

                <!-- success message -->
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


                <!-- error message -->
                <div class="contents-big-padding" id="container-error" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                        unset($_SESSION['error_display']); ?>;">
                    <div class="container-error">
                        <div class="container-error-description">
                            <?php if (isset($_SESSION['error_message'])) {
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-success-close" onclick="closeErrorStatus()">&times;</button>
                    </div>

                </div>


                <div class="row row-between title-search">

                    <div class="contents-title">
                        Userpage
                    </div>


                    <!-- loading animation -->
                    <div id="loading-overlay">
                        <div class="spinner"></div>
                    </div>


                    <!-- search field -->
                    <form action="results_search.php" method="GET" class="container-search row">
                        <input type="text" class="search" id="search" name="query" autocomplete="off" placeholder="Search by title">

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



                <?php
                    $patrons_id = $_SESSION['patrons_id'] ?? null;
                    $number = 10;

                    // Function to run Python script and return book IDs
                    function get_book_ids_from_python($pythonScript, $patrons_id, $number)
                    {
                        return json_decode(shell_exec("py ../python_algorithm/$pythonScript $patrons_id $number"), true);
                    }

                    // Function to fetch book details from the database based on book IDs
                    function fetch_books_from_ids($pdo, $book_ids, $patrons_id)
                    {
                        if ($book_ids && count($book_ids) > 0) {
                            $book_ids_str = implode(',', array_map('intval', $book_ids));
                            $sql = "SELECT 
                                        b.book_id, 
                                        b.title, 
                                        b.copies,
                                        c.category AS category_name, 
                                        b.image,
                                        COUNT(br_all.borrow_id) AS borrow_count,
                                         CASE 
                                            WHEN b.copies > (
                                                SELECT COUNT(*) 
                                                FROM borrow br2 
                                                WHERE br2.book_id = b.book_id 
                                                AND br2.status != 'Returned'
                                            ) THEN 'Available'
                                            ELSE 'Unavailable'
                                        END AS book_status
                                    FROM 
                                        books b
                                    LEFT JOIN 
                                        category c ON b.category_id = c.category_id
                                    LEFT JOIN 
                                        borrow br_all ON b.book_id = br_all.book_id  
                                    LEFT JOIN 
                                        borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id  
                                    WHERE 
                                        b.book_id IN ($book_ids_str)
                                    GROUP BY 
                                        b.book_id
                                    ORDER BY 
                                        FIELD(b.book_id, $book_ids_str);
                                    ";

                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
                            $stmt->execute();

                            return $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        return [];
                    }

                    // Combined check to see if the user has borrowed or rated any books
                    $userDataQuery = "
                                        SELECT 
                                            (SELECT COUNT(*) FROM borrow WHERE patrons_id = :patrons_id AND status = 'Returned') AS borrow_count,
                                            (SELECT COUNT(*) FROM ratings WHERE patrons_id = :patrons_id) AS rating_count";
                    $stmt = $pdo->prepare($userDataQuery);
                    $stmt->execute(['patrons_id' => $patrons_id]);
                    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                    $hasBorrowedBooks = $userData['borrow_count'] > 0;
                    $hasRatedBooks = $userData['rating_count'] > 0;

                    // Retrieve recommendation book IDs based on user's data
                    $recommendations = get_book_ids_from_python('borrow_ratings_cbf_tfidf.py', $patrons_id, $number);

                    // Fetch recommendations for borrowing-based and rating-based methods
                    if ($hasBorrowedBooks) {
                        $books_borrow_cf = fetch_books_from_ids($pdo, get_book_ids_from_python('borrow_cf_als.py', $patrons_id, $number), $patrons_id);
                        $books_borrow_cbf = fetch_books_from_ids($pdo, $recommendations['borrow_cbf'], $patrons_id);
                    } else {
                        include 'functions/fetch_most_borrowed.php';
                    }

                    if ($hasRatedBooks) {
                        $books_rating_cf = fetch_books_from_ids($pdo, get_book_ids_from_python('ratings_cf_als.py', $patrons_id, $number), $patrons_id);
                        $books_rating_cbf = fetch_books_from_ids($pdo, $recommendations['rating_cbf'], $patrons_id);
                    } else {
                        include 'functions/fetch_top_rated.php';
                        include 'functions/fetch_most_rated_user_interest.php';
                    }
                    ?>


                    <!-- Section for Borrow-Based Collaborative Filtering (CF) -->
                    <?php if ($hasBorrowedBooks && !empty($books_borrow_cf)): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Based on your borrowing habits</div>
                                <div class="button button-view-more-recommendation" data-category="Based on your borrowing habits">View More</div>
                            </div>
                            <div class="row-books-container">

                                <?php
                                $recommend_books = $books_borrow_cf;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Borrow-Based Content based Filtering (CBF) -->
                    <?php if ($hasBorrowedBooks && !empty($books_borrow_cbf)): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Based on your latest borrow</div>
                                <div class="button button-view-more-recommendation" data-category="Based on your latest borrow">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_borrow_cbf;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Ratings Collaborative Filtering (CF) -->
                    <?php if ($hasRatedBooks && !empty($books_rating_cf)): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Based on your rating behaviour</div>
                                <div class="button button-view-more-recommendation" data-category="Based on your rating behaviour">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_rating_cf;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Ratings Content-Based Filtering (CBF) -->
                    <?php if ($hasRatedBooks && !empty($books_rating_cbf)): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Based on your latest rated book</div>
                                <div class="button button-view-more-recommendation" data-category="Based on your latest rated book">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_rating_cbf;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Interest -->
                    <?php if (!$hasRatedBooks): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Most rated books according to your interests</div>
                                <div class="button button-view-more-recommendation" data-category="Most rated books according to your interests">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_most_rated_user_interest;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Most Borrowed Books -->
                    <?php if (!$hasBorrowedBooks): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Most borrowed book</div>
                                <div class="button button-view-more-recommendation" data-category="Most borrowed book">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_most_borrowed;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>



                    <!-- Section for Top Rated Books -->
                    <?php if (!$hasRatedBooks): ?>
                        <div class="contents-big-padding">
                            <div class="row row-between">
                                <div>Top rated book</div>
                                <div class="button button-view-more-recommendation" data-category="Top rated book">View More</div>
                            </div>
                            <div class="row-books-container">
                                <?php
                                $recommend_books = $books_top_rated;
                                include 'books_display.php';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>







                    <!-- categories based on user preference -->
                    <?php foreach ($books_limit as $category => $bookDetails): ?>
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
                                            <div class="patrons-id" style="display: none;"><?php echo $patrons_id  ?></div>

                                            <?php
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

                                                <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" loading="lazy">
                                            </div>

                                            <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                            <div class="books-copies" style="display: none;"><?php echo htmlspecialchars($book['copies']); ?></div>
                                            <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>
                                            <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>


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





            <!-- display books when click -->

            <div class="row-books-contents-modal-parent" id="book-details" style="display: none;">

                <div class="row-books-contents-modal">

                    <div class="container-books-contents-modal">

                        <div class="books-contents-id" style="display: none;">ID</div>

                        <div class="books-contents-image">Image</div>

                        <div class="books-contents">

                            <div class="books-contents">

                                <div class="row row-between">

                                    <div class="books-contents-borrow-status" style="display:none;"></div>

                                    <div class="books-contents-favorite" style="display:none;"></div>

                                    <div class="books-contents-category"></div>

                                    <div class="button button-close">&times;</div>

                                </div>

                                <div class="books-contents-name">Book Sample</div>


                                <div class="row">
                                    <div class="books-contents-author">Book Author</div>
                                    <div class="books-contents-copyright">0000</div>
                                </div>


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


                                <div class="row row-right">

                                    <div class="books-contents-status-message">Available 1 out of 1 copies</div>

                                </div>

                            </div>




                            <?php include 'modal/add_rating_modal.php'; ?>


                        </div>


                    </div>




                </div>

                <script src="js/book-details-toggle-fetching.js"></script>

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

<script>
    const patronId = <?php echo json_encode($patrons_id); ?>;
    const addPatronId = <?php echo json_encode($patrons_id); ?>;
    const removePatronId = <?php echo json_encode($patrons_id); ?>;
</script>

<script src="js/borrow-submit.js"></script>
<script src="js/add-favorites-submit.js"></script>
<script src="js/remove-favorites-submit.js"></script>
<script src="js/loading-animation.js"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.button-view-more').forEach(function(button) {
            button.addEventListener('click', function() {
                var category = this.getAttribute('data-category');
                var encodedCategory = encodeURIComponent(category);
                window.location.href = 'results_more.php?category=' + encodedCategory;
            });
        });
    });
</script>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.button-view-more-recommendation').forEach(function(button) {
            button.addEventListener('click', function() {
                var category = this.getAttribute('data-category');
                var encodedCategory = encodeURIComponent(category);
                window.location.href = 'results_recommendation_more.php?more=' + encodedCategory;
            });
        });
    });
</script>



<script>
    const bookList = <?php echo json_encode($books); ?>; // JSON-encoded array of books

    const searchInput = document.getElementById('search');

    searchInput.addEventListener('input', function() {
        const input = this.value.trim().toLowerCase();
        let suggestions = [];

        // Iterate through each category and filter matching books
        Object.values(bookList).forEach(bookCategory => {
            bookCategory.forEach(book => {
                if (book.title.toLowerCase().includes(input)) {
                    suggestions.push(book); // Push the entire book object for later use
                }
            });
        });

        // Limit to top 10 suggestions
        suggestions = suggestions.slice(0, 10);

        // Clear previous suggestions
        let datalist = document.getElementById('datalist-search');
        if (datalist) {
            datalist.remove();
        }

        // Create new datalist for suggestions
        datalist = document.createElement('datalist');
        datalist.id = 'datalist-search';

        suggestions.forEach(book => {
            const option = document.createElement('option');
            option.value = book.title; // Display the book title
            option.dataset.bookId = book.book_id; // Attach book_id as data attribute
            option.dataset.author = book.author; // Attach author as data attribute
            option.dataset.image = book.image; // Attach image as data attribute
            option.dataset.avgRating = book.avg_rating; // Attach average rating
            option.dataset.borrowStatus = book.borrow_status; // Attach borrow status
            option.dataset.favoriteStatus = book.favorite_status; // Attach favorite status
            option.dataset.patronRating = book.patron_rating; // Attach patron rating
            option.dataset.categoryName = book.category_name; // Attach category name as data attribute
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);
        searchInput.setAttribute('list', 'datalist-search');
    });

    // Event listener for when the user selects a suggestion
    searchInput.addEventListener('change', function() {
        const selectedTitle = this.value.trim();
        const selectedBook = findBookByTitle(selectedTitle);

        // If a valid book is selected, redirect to results_recommendation.php
        if (selectedBook) {
            submitBookDetails(selectedBook);
        }
    });

    // Function to find a book by title
    function findBookByTitle(title) {
        for (const bookCategory of Object.values(bookList)) {
            for (const book of bookCategory) {
                if (book.title === title) {
                    return book; // Return the matching book object
                }
            }
        }
        return null; // Return null if no matching book is found
    }

    // Function to submit the selected book details
    function submitBookDetails(book) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'results_recommendation.php';

        // Create hidden inputs for book details
        const fields = [{
                name: 'book_id',
                value: book.book_id
            },
            {
                name: 'title',
                value: book.title
            },
            {
                name: 'author',
                value: book.author
            },
            {
                name: 'image',
                value: book.image
            },
            {
                name: 'avg_rating',
                value: book.avg_rating
            }, // Optional: include average rating
            {
                name: 'borrow_status',
                value: book.borrow_status
            }, // Optional: include borrow status
            {
                name: 'favorite_status',
                value: book.favorite_status
            }, // Optional: include favorite status
            {
                name: 'patron_rating',
                value: book.patron_rating
            }, // Optional: include patron rating
            {
                name: 'category_name',
                value: book.category_name
            } // Include category name
        ];

        fields.forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = field.name;
            input.value = field.value;
            form.appendChild(input);
        });

        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
</script>