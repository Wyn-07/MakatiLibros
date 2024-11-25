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


<?php include '../connection.php'; ?>


<body>
    <div class="wrapper">

        <div class="container-top">
            <div class="row row-between-top">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="../images/library-logo.png" class="image">
                    </div>
                    Makati City Hall Library <a href="../../admin/login.php" style="color:#393E46">a</a>
                </div>


                <div class="container-navigation">

                    <a href="../index.php" class="container-home"><img src="../images/home-white.png"
                            class="image"></a>

                    <a href="login.php" class="navigation-contents">LOG IN</a>

                    <a href="signup.php" class="navigation-contents">SIGN UP</a>

                </div>

            </div>
        </div>

        <div id="overlay" class="overlay"></div>


        <div class="row-body">

            <div class="container-sidebar" id="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="container-content">



                <div class="row row-between title-search">

                    <?php

                    // Include the fetch_category function
                    include 'functions/fetch_category.php';

                    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

                    // Prepare the SQL query with a JOIN to the category table
                    $query = "SELECT 
                                b.category_id, 
                                c.category AS category_name, 
                                b.title, 
                                b.image, 
                                b.book_id, 
                                b.author_id,
                                b.copyright, 
                                a.author,
                                IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating,
                                CASE 
                                    WHEN br2.borrow_id IS NOT NULL AND br2.status != 'Returned' THEN 'Unavailable' 
                                    ELSE 'Available' 
                                END AS book_status 
                            FROM books b
                            LEFT JOIN 
                                author a ON b.author_id = a.author_id 
                            LEFT JOIN 
                                category c ON b.category_id = c.category_id 
                            LEFT JOIN 
                                ratings r ON b.book_id = r.book_id
                            LEFT JOIN 
                                borrow br2 ON b.book_id = br2.book_id  -- Check for any borrow entry
                            LEFT JOIN 
                                condemned cd ON b.book_id = cd.book_id 
                            LEFT JOIN 
                                missing ms ON b.book_id = ms.book_id 
                            WHERE 
                                cd.book_id IS NULL AND ms.book_id IS NULL";

                    // Add the category filter to the query if it exists
                    if ($categoryFilter) {
                        $query .= " AND c.category LIKE :categoryFilter";
                    }

                    // Add GROUP BY clause
                    $query .= " GROUP BY b.book_id, b.category_id, c.category, b.title, b.copyright, b.image, b.author_id, a.author";

                    // Prepare the PDO statement
                    $stmt = $pdo->prepare($query);

                    // Bind the category filter with wildcards if it exists
                    if ($categoryFilter) {
                        $stmt->bindValue(':categoryFilter', '%' . $categoryFilter . '%', PDO::PARAM_STR);
                    }

                    $stmt->execute();

                    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    ?>




                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($categoryFilter); ?>"
                    </div>


                    <div class="container-search row">
                        <input type="text" id="search" placeholder="Search by title" class="search">
                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>

                </div>



                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>



                <div class="result-contents">

                    <div class="row-contents-center" id="bookContainer">
                        <?php if ($books): ?>
                            <?php foreach ($books as $book): ?>
                                <div class="container-books-2">

                                    <div class="books-id" style="display: none"><?php echo htmlspecialchars($book['book_id']); ?></div>

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


                                    <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>

                                    <div class="books-categories" style="display: none"><?php echo htmlspecialchars($book['category_name']); ?></div>

                                    <div class="books-ratings" style="display: none"><?php echo htmlspecialchars($book['avg_rating']); ?></div>

                                    <div class="books-name-2"><?= htmlspecialchars($book['title']) ?></div>
                                    <div class="books-author" style="display: none"><?= htmlspecialchars($book['author']) ?></div>
                                    <div class="books-copyright" style="display: none"><?php echo htmlspecialchars($book['copyright']); ?></div>

                                </div>


                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-results">No books found for the selected category.</div>
                        <?php endif; ?>
                    </div>




                </div>


                <div class="row row-center">
                    <div class="pagination-controls">
                        Items per page:
                        <select class="page-select" id="itemsPerPage">
                            <option value="20" selected>20</option>
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


            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Select DOM elements
                    const searchInput = document.getElementById('search');
                    const bookContainer = document.getElementById('bookContainer');
                    const notFoundMessage = document.createElement('div');
                    notFoundMessage.id = 'not-found-message';
                    notFoundMessage.textContent = 'No books found';
                    notFoundMessage.style.display = 'none';
                    bookContainer.parentElement.appendChild(notFoundMessage);

                    const itemsPerPageInput = document.getElementById('itemsPerPage');
                    const prevPageButton = document.getElementById('prevPage');
                    const nextPageButton = document.getElementById('nextPage');
                    const pageInfo = document.getElementById('pageInfo');

                    // Initialize pagination variables
                    let currentPage = 1;
                    let itemsPerPage = parseInt(itemsPerPageInput.value, 10);
                    let visibleBooks = [];
                    let totalItems = 0;
                    let totalPages = 0;

                    // Function to normalize strings for search
                    function normalizeString(str) {
                        return str.toLowerCase().replace(/\s+/g, '-');
                    }

                    // Function to filter books based on search term
                    function filterBooks() {
                        const searchTerm = searchInput.value.toLowerCase();
                        visibleBooks = [];

                        // Get all books
                        const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));

                        books.forEach(book => {
                            const title = book.querySelector('.books-name-2').textContent.toLowerCase();
                            const author = book.querySelector('.books-author')?.textContent.toLowerCase() || '';

                            // Match search term with title or author
                            if (title.includes(searchTerm) || author.includes(searchTerm)) {
                                visibleBooks.push(book);
                            } else {
                                book.style.display = 'none';
                            }
                        });

                        // Display 'Not Found' message if no books match
                        if (visibleBooks.length === 0) {
                            notFoundMessage.style.display = 'block';
                        } else {
                            notFoundMessage.style.display = 'none';
                        }

                        totalItems = visibleBooks.length;
                        totalPages = Math.ceil(totalItems / itemsPerPage);
                        currentPage = 1;

                        updatePagination(); // Update pagination after filtering
                    }

                    // Function to update pagination
                    function updatePagination() {
                        const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));
                        books.forEach(book => book.style.display = 'none'); // Hide all books

                        // Show books for the current page
                        const startIndex = (currentPage - 1) * itemsPerPage;
                        const endIndex = Math.min(startIndex + itemsPerPage, visibleBooks.length);

                        for (let i = startIndex; i < endIndex; i++) {
                            visibleBooks[i].style.display = 'block';
                        }

                        // Update page info
                        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                        prevPageButton.disabled = currentPage === 1;
                        nextPageButton.disabled = currentPage === totalPages;
                    }

                    // Event listener for search input
                    searchInput.addEventListener('input', filterBooks);

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

                    // Event listener for changing items per page
                    itemsPerPageInput.addEventListener('change', (event) => {
                        itemsPerPage = parseInt(event.target.value, 10);
                        filterBooks(); // Recalculate pagination based on new items per page
                    });

                    // Initialize pagination and show the first 20 books by default
                    const books = Array.from(bookContainer.querySelectorAll('.container-books-2'));
                    visibleBooks = books.slice();
                    totalItems = visibleBooks.length;
                    totalPages = Math.ceil(totalItems / itemsPerPage);

                    updatePagination(); // Ensure books are displayed correctly on load
                });
            </script>


        </div>





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

                        <div class="books-contents-ratings" style="display:none"></div>
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
                                <div class="ratings-number"></div>&nbspout of 5
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <script src="js/book-details-toggle-result-more.js"></script>

        </div>





        <div class="container-footer">
            <?php include 'footer.php'; ?>
        </div>


    </div>
</body>



</html>

<script src="js/sidebar.js"></script>
<!-- <script src="js/book-list-pagination.js"></script> -->

<script src="js/close-status.js"></script>
<!-- <script src="js/tooltips.js"></script> -->


<script src="js/loading-animation.js"></script>