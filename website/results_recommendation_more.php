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

<?php session_start(); ?>

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

            $original_query = isset($_GET['more']) ? urldecode($_GET['more']) : null;

            $patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

            $number = 60;

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

                    $sql =
                        "SELECT 
                                b.book_id, 
                                b.title,
                                b.copyright, 
                                a.author  AS author, 
                                c.category AS category_name, 
                                b.image,
                                IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating, 
                                br.status AS borrow_status, 
                                f.status AS favorite_status, 
                                pr.ratings AS patron_rating,
                                CASE 
                                    WHEN br2.borrow_id IS NOT NULL AND br2.status != 'Returned' THEN 'Unavailable' 
                                    ELSE 'Available' 
                                END AS book_status
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
                            LEFT JOIN 
                                borrow br2 ON b.book_id = br2.book_id  -- Check for any borrow entry
                            WHERE 
                                b.book_id IN ($book_ids_str)
                            GROUP BY 
                                b.book_id
                            ORDER BY 
                                FIELD(b.book_id, $book_ids_str)
                        ";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
                    $stmt->execute();

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                return [];
            }


            if ($original_query == "Based on your borrowing habits") {
                $books_borrow_cf = fetch_books_from_ids($pdo, get_book_ids_from_python('borrow_cf_als.py', $patrons_id, $number), $patrons_id);
                $recommend_books = $books_borrow_cf;
            } else if ($original_query == "Based on your latest borrow") {
                $books_borrow_cbf = fetch_books_from_ids($pdo, get_book_ids_from_python('borrow_cbf_tfidf.py', $patrons_id, $number), $patrons_id);
                $recommend_books = $books_borrow_cbf;
            } else if ($original_query == "Based on your rating behaviour") {
                $books_rating_cf = fetch_books_from_ids($pdo, get_book_ids_from_python('ratings_cf_als.py', $patrons_id, $number), $patrons_id);
                $recommend_books = $books_rating_cf;
            } else if ($original_query == "Based on your latest rated book") {
                $books_rating_cbf = fetch_books_from_ids($pdo, get_book_ids_from_python('ratings_cbf_tfidf.py', $patrons_id, $number), $patrons_id);
                $recommend_books = $books_rating_cbf;
            } else if ($original_query == "Most borrowed book") {
                include 'functions/fetch_most_borrowed.php';
                $recommend_books = $books_most_borrowed;
            } else if ($original_query == "Most rated book") {
                include 'functions/fetch_most_rated.php';
                $recommend_books = $books_most_rated;
            } else if ($original_query == "Top rated book") {
                include 'functions/fetch_top_rated.php';
                $recommend_books = $books_top_rated;
            } else if ($original_query == "Most rated books according to your interests") {
                include 'functions/fetch_most_rated_user_interest.php';
                $recommend_books = $books_most_rated_user_interest;
            }

            ?>



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
                        <?php echo htmlspecialchars($original_query); ?>
                    </div>



                    <!-- loading animation -->
                    <div id="loading-overlay">
                        <div class="spinner"></div>
                    </div>



                    <div class="container-search row">
                        <input type="text" id="search" class="search" placeholder="Search by title or author">

                        <div class="container-search-image" onclick="goToResults()">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row media-column">

                    <div class="container-filter">

                        <div class="row auto">
                            <div class="filter-content-image">
                                <img src="../images/filter-black.png" class="image">
                            </div>
                            <div class="filter-content-title">Search Filters</div>
                        </div>

                        <form id="filter-form">
                            <div class="filter-content">
                                <div class="filter-container-item">
                                    <div class="filter-title">By Category</div>
                                    <?php foreach ($category as $categories):
                                        $category_slug = strtolower(str_replace(' ', '-', $categories)); // Create a slug for the category
                                    ?>
                                        <div class="filter-item">
                                            <input type="checkbox" id="<?= $category_slug ?>" name="category[]" value="<?= $category_slug ?>">
                                            <label for="<?= $category_slug ?>"> <?= htmlspecialchars($categories) ?> </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="filter-container-item">
                                    <div class="filter-title">By Date</div>
                                    <input type="date" class="filter-date" name="filter_date">
                                </div>

                                <div class="button button-clear" id="clear-filters">Clear All</div>
                            </div>
                        </form>

                    </div>


                    <div id="search-results" class="container-contents-body">


                        <div class="row-contents-center" id="bookContainer">
                            <?php if (!empty($recommend_books)): ?>
                                <?php foreach ($recommend_books as $book): ?>

                                    <div class="container-books-2">
                                        <div class="books-id" style="display: none;"><?php echo htmlspecialchars($book['book_id']); ?></div>

                                        <div class="books-image-2">
                                            <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image">
                                        </div>

                                        <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>

                                        <div class="books-categories" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                        <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                                        <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                        <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                        <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['patron_rating']); ?></div>

                                        <div class="books-name-2"><?php echo htmlspecialchars($book['title']); ?></div>
                                        <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author']); ?></div>
                                        <div class="books-copyright" style="display: none"><?php echo htmlspecialchars($book['copyright']); ?></div>

                                        <!-- Hidden form for borrowing books -->
                                        <form id="borrowForm" action="functions/borrow_books.php" method="POST" style="display: none;">
                                            <input type="hidden" name="book_id" id="bookIdInput">
                                            <input type="hidden" name="patrons_id" id="patronIdInput">
                                            <input type="hidden" name="status" value="Pending">
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


                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No results found.</p>
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


        <div class="container-footer">
            <?php include 'footer.php'; ?>
        </div>


    </div>
</body>



</html>

<script src="js/sidebar.js"></script>
<script src="js/close-status.js"></script>


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
    // Function to handle filter changes
    function handleFilterChange() {
        const selectedCategories = [];
        const checkboxes = document.querySelectorAll('input[name="category[]"]:checked');

        checkboxes.forEach(checkbox => {
            selectedCategories.push(checkbox.value);
        });

        const selectedDate = document.querySelector('input[name="filter_date"]').value;

        // Call your filtering function with the selected categories and date
        filterBooks(selectedCategories, selectedDate);
    }

    // Add event listeners to checkboxes
    const checkboxes = document.querySelectorAll('input[name="category[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleFilterChange);
    });

    // Optionally, add an event listener for the date input
    const dateInput = document.querySelector('input[name="filter_date"]');
    if (dateInput) {
        dateInput.addEventListener('change', handleFilterChange);
    }

    // Placeholder function for filtering logic
    function filterBooks(categories, date) {
        // Your filtering logic goes here
        console.log('Selected categories:', categories);
        console.log('Selected date:', date);
        // Implement the logic to filter the books based on selected categories and date
    }
</script>
<!-- <script src="js/book-list-pagination.js"></script> -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select DOM elements
        const searchInput = document.getElementById('search');
        const categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');
        const dateInput = document.querySelector('input[name="filter_date"]');
        const clearFiltersButton = document.getElementById('clear-filters');
        const bookContainer = document.getElementById('bookContainer');
        const notFoundMessage = document.getElementById('not-found-message');
        const itemsPerPageInput = document.getElementById('itemsPerPage');
        const prevPageButton = document.getElementById('prevPage');
        const nextPageButton = document.getElementById('nextPage');
        const pageInfo = document.getElementById('pageInfo');

        // Initialize pagination variables
        let currentPage = 1;
        let itemsPerPage = parseInt(itemsPerPageInput.value, 10);
        let totalItems = 0;
        let totalPages = 0;
        let visibleBooks = [];

        // Function to normalize category strings
        function normalizeString(str) {
            return str.toLowerCase().replace(/\s+/g, '-');
        }

        // Function to filter books
        function filterBooks() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategories = Array.from(categoryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => normalizeString(checkbox.value));
            const selectedDate = dateInput.value ? new Date(dateInput.value) : null;

            const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));
            visibleBooks = [];

            books.forEach(book => {
                const title = book.querySelector('.books-name-2').textContent.toLowerCase();
                const author = book.querySelector('.books-author').textContent.toLowerCase();
                const categories = book.querySelector('.books-categories').textContent.toLowerCase().split(',')
                    .map(cat => normalizeString(cat.trim()));
                const bookDateText = book.querySelector('.books-copyright').textContent.trim();
                const bookDate = bookDateText ? new Date(`${bookDateText}-01-01`) : null;

                const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
                const matchesCategory = selectedCategories.length === 0 || selectedCategories.some(category => categories.includes(category));
                const matchesDate = !selectedDate || (bookDate && bookDate >= selectedDate);

                if (matchesSearch && matchesCategory && matchesDate) {
                    visibleBooks.push(book);
                }
            });

            notFoundMessage.style.display = visibleBooks.length > 0 ? 'none' : 'flex';

            totalItems = visibleBooks.length;
            totalPages = Math.ceil(totalItems / itemsPerPage);
            currentPage = 1; // Reset to the first page
            updatePagination();
        }

        // Function to update pagination
        function updatePagination() {
            // Hide all books first
            const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));
            books.forEach(book => book.style.display = 'none');

            // Show books for the current page
            for (let i = (currentPage - 1) * itemsPerPage; i < currentPage * itemsPerPage && i < visibleBooks.length; i++) {
                visibleBooks[i].style.display = 'block';
            }

            // Update page info
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevPageButton.disabled = currentPage === 1;
            nextPageButton.disabled = currentPage === totalPages;
        }

        // Event listeners for filtering
        searchInput.addEventListener('input', filterBooks);
        categoryCheckboxes.forEach(checkbox => checkbox.addEventListener('change', filterBooks));
        dateInput.addEventListener('change', filterBooks);

        // Clear filters button
        clearFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            categoryCheckboxes.forEach(checkbox => checkbox.checked = false);
            dateInput.value = '';
            filterBooks();
        });

        // Event listeners for pagination controls
        prevPageButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        nextPageButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        itemsPerPageInput.addEventListener('change', (event) => {
            itemsPerPage = parseInt(event.target.value, 10);
            filterBooks(); // Recalculate pagination based on new items per page
        });

        // Initialize pagination
        filterBooks(); // Ensure books are filtered and paginated on load
    });
</script>