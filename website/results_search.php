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
<?php include 'functions/fetch_category_name.php'; ?>


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
            if (isset($_GET['query'])) {
                $original_query = $_GET['query'];
                $query = '%' . $original_query . '%'; // Add wildcard for LIKE

                // Assume that you have a way to get the logged-in patron's ID
                $patrons_id = $_SESSION['patrons_id']; // Example: Getting patron ID from session

                $sql =
                    "SELECT 
                    b.category_id, 
                    c.category AS category_name, 
                    b.title, 
                    b.image, 
                    b.book_id, 
                    b.copyright, 
                    b.author_id, 
                    a.author AS author, 
                    IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating,
                    br.status AS borrow_status, -- Fetch the borrow status specific to the patron
                    f.status AS favorite_status,
                    pr.ratings AS patron_rating,
                    CASE 
                        WHEN br2.borrow_id IS NOT NULL AND br2.status != 'Returned' THEN 'Unavailable' 
                        ELSE 'Available' 
                    END AS book_status
                FROM 
                    books b
                LEFT JOIN 
                    author a ON b.author_id = a.author_id -- Join to get the author's name
                LEFT JOIN 
                    category c ON b.category_id = c.category_id -- Join to get the category name
                LEFT JOIN 
                    ratings r ON b.book_id = r.book_id
                LEFT JOIN 
                    borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id -- Join to get the borrow status specific to the patron
                LEFT JOIN 
                    favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id -- Join to get the favorite status specific to the patron
                LEFT JOIN 
                    ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id 
                LEFT JOIN 
                    borrow br2 ON b.book_id = br2.book_id 
                LEFT JOIN 
                    condemned cd ON b.book_id = cd.book_id -- Left join with condemned table
                LEFT JOIN 
                    missing ms ON b.book_id = ms.book_id -- Left join with missing table
                WHERE   
                    cd.book_id IS NULL AND ms.book_id IS NULL AND (b.title LIKE :query OR a.author LIKE :query)
                GROUP BY 
                    b.book_id, b.category_id, c.category, b.title, b.image, b.author_id, a.author, br.status, f.status, pr.ratings";

                // Prepare the statement
                $stmt = $pdo->prepare($sql);

                // Bind the parameters
                $stmt->bindParam(':query', $query, PDO::PARAM_STR);
                $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT); // Bind patrons_id

                // Execute the statement
                $stmt->execute();

                // Fetch all results
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            ?>



            <div class="container-content">

                <div class="row row-between title-search">

                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($original_query); ?>"
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
                                    <div class="filter-title">By Year</div>
                                    <select class="filter-date" name="filter-date">
                                        <option value="">Select Year</option>
                                        <script>
                                            const currentYear = new Date().getFullYear();
                                            for (let year = currentYear; year >= 1900; year--) {
                                                document.write(`<option value="${year}">${year}</option>`);
                                            }
                                        </script>
                                    </select>
                                </div>



                                <div class="button button-clear" id="clear-filters">Clear All</div>
                            </div>
                        </form>

                    </div>



                    <div id="search-results" class="container-contents-body">

                        <div class="row-contents-center" id="bookContainer">
                            <?php if (!empty($result)): ?>
                                <?php foreach ($result as $book): ?>

                                    <div class="container-books-2">
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


                                        <div class="books-image-2">
                                            <div class="books-status-show <?php echo $statusCategoryClass; ?>" <?php echo $hideStatus ? 'style="display: none;"' : ''; ?>>
                                                <?php echo htmlspecialchars($book['book_status']); ?>
                                            </div>

                                            <div class="books-status-category <?php echo $statusCategoryClass; ?>">
                                                <?php echo $statusCategoryText; ?>
                                            </div>

                                            <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" loading="lazy">
                                        </div>

                                        <div class="books-categories" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                        <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
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



                        <div class="row-books-contents-modal-parent" id="book-details" style="display: none;">

                            <div class="row-books-contents-modal">
                                <div class="container-books-contents-modal">

                                    <div class="books-contents-id" style="display: none;">ID</div>

                                    <div class="books-contents-image">Image</div>

                                    <div class="books-contents">

                                        <div class="row row-between">

                                            <div class="books-contents-borrow-status" style="display:none;"></div>

                                            <div class="books-contents-category"></div>

                                            <div class="books-contents-status" style="display:none;"></div>

                                            <div class="books-contents-favorite" style="display:none;"></div>

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

                                        <?php include 'modal/add_rating_modal.php'; ?>

                                    </div>
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
        const dateInput = document.querySelector('.filter-date'); // Fixed selector
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
            const selectedYear = dateInput.value ? parseInt(dateInput.value, 10) : null;

            const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));
            visibleBooks = [];

            books.forEach(book => {
                const title = book.querySelector('.books-name-2').textContent.toLowerCase();
                const author = book.querySelector('.books-author').textContent.toLowerCase();
                const categories = book.querySelector('.books-categories').textContent.toLowerCase().split(',')
                    .map(cat => normalizeString(cat.trim()));
                const bookDateText = book.querySelector('.books-copyright').textContent.trim();
                const bookYear = bookDateText ? parseInt(bookDateText, 10) : null;

                const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
                const matchesCategory = selectedCategories.length === 0 || selectedCategories.some(category => categories.includes(category));
                const matchesDate = !selectedYear || (bookYear && bookYear === selectedYear);

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
        dateInput.addEventListener('change', filterBooks); // Fixed event listener

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

<!-- <script>
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
</script> -->




