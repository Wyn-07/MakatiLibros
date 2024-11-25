<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>


<?php

session_start();

include '../connection.php';

include 'functions/fetch_category_name.php';

include 'functions/fetch_favorites.php';

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
                            Favorite Books
                        </div>
                        <div class="profile-subtitle-white">
                            Browse your list of favorite books.
                        </div>
                        <div class="profile-subtitle-white">
                            Keep track of the books you love the most.
                        </div>
                    </div>
                </div>



                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>



                <div class="profile-contents">


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
                                            <div id="favorites" class="profile-nav-items nav-favorites" onclick="toggleSection('myFavorites')">My Favorites</div>
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


                        <div style="width: 100%;">

                            <!-- success message -->
                            <div id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                        unset($_SESSION['success_display']); ?>;">
                                <div class="container-success" style="margin-bottom: 10px">
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
                            <div id="container-error" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                        unset($_SESSION['error_display']); ?>;">
                                <div class="container-error" style="margin-bottom: 10px">
                                    <div class="container-error-description">
                                        <?php if (isset($_SESSION['error_message'])) {
                                            echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']);
                                        } ?>
                                    </div>
                                    <button type="button" class="button-success-close" onclick="closeErrorStatus()">&times;</button>
                                </div>

                            </div>

                            <div class="profile-container-search">
                                <div class="profile-container-search-image">
                                    <div class="search-image">
                                        <img src="../images/search-black.png" class="image">
                                    </div>
                                </div>
                                <input type="text" id="search" class="search" placeholder="Search here..." autocomplete="off">
                            </div>


                            <div class="profile-container-white-row" style="width: 100%;">


                                <div class="container-filter-2">

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


                                <div id="search-results" class="container-contents-body-2">


                                    <div class="row-contents-center" id="bookContainer">
                                        <?php if (!empty($books)): ?>
                                            <?php foreach ($books as $book): ?>

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

                                                    <div class="books-name-2"><?php echo htmlspecialchars($book['title']); ?></div>

                                                    <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author_name']); ?></div>
                                                    <div class="books-copyright" style="display: none"><?php echo htmlspecialchars($book['copyright']); ?></div>


                                                    <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>
                                                    <div class="books-categories" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                                    <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>


                                                    <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                                    <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                                    <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['user_rating']); ?></div>


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



                                    <!-- display books when click -->

                                    <div class="row-books-contents-modal-parent" id="book-details" style="display: none;">


                                        <div class="row-books-contents-modal">

                                            <div class="container-books-contents-modal">

                                                <div class="books-contents-id" style="display: none;">ID</div>

                                                <div class="books-contents-image">Image</div>

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


                                <!-- <div id="no-results-container" style="display: none; min-height: 300px; justify-content: center; align-items: center; flex-direction: column; width: 100%; background-color: white; padding: 20px 40px; margin-bottom: 10px;">
                                <div class="unavailable-image">
                                    <img src="../images/no-books.png" class="image" alt="No Books Available">
                                </div>
                                <div class="unavailable-text">No Results</div>
                            </div> -->


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


<!-- search script -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const bookContainers = document.querySelectorAll('.container-books-2');
    const noResultsContainer = document.querySelector('#no-results-container');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');

    let currentPage = 1;
    let itemsPerPage = parseInt(itemsPerPageSelect.value);
    let filteredBooks = Array.from(bookContainers);

    function updatePageInfo() {
        pageInfo.textContent = `Page ${currentPage}`;
    }

    function paginateBooks() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        filteredBooks.forEach((book, index) => {
            if (index >= startIndex && index < endIndex) {
                book.style.display = 'flex';
            } else {
                book.style.display = 'none';
            }
        });
    }

    function filterBooks() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredBooks = Array.from(bookContainers).filter((book) => {
            const title = book.querySelector('.books-name-2').textContent.toLowerCase();
            const author = book.querySelector('.books-author').textContent.toLowerCase();
            const copyright = book.querySelector('.books-copyright').textContent.toLowerCase();

            return title.includes(searchTerm) || author.includes(searchTerm) || copyright.includes(searchTerm);
        });

        if (filteredBooks.length === 0) {
            noResultsContainer.style.display = 'flex';
        } else {
            noResultsContainer.style.display = 'none';
        }

        // Reset to the first page after filtering
        currentPage = 1;
        updatePageInfo();
        paginateBooks();
    }

    // Handle search input
    searchInput.addEventListener('input', filterBooks);

    // Handle items per page change
    itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(itemsPerPageSelect.value);
        paginateBooks();
    });

    // Handle previous page click
    prevPageButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePageInfo();
            paginateBooks();
        }
    });

    // Handle next page click
    nextPageButton.addEventListener('click', function() {
        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePageInfo();
            paginateBooks();
        }
    });

    // Initial pagination setup
    filterBooks(); // Filter and paginate on page load
});
</script> -->