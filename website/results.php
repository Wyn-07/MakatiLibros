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

<?php
session_start();
$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<?php include '../connection.php'; ?>


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

                    <?php

                    // Include the fetch_category function
                    include 'functions/fetch_category.php';
                    // Get the category filter
                    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

                    // Prepare the SQL query with a JOIN to the category table
                    $query = "SELECT 
                                b.category_id, 
                                c.category AS category_name, 
                                b.title, 
                                b.image, 
                                b.book_id, 
                                b.author_id, 
                                a.author, -- Fetch the author's name
                                IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating,
                                br.status AS borrow_status, -- Fetch the borrow status specific to the patron
                                f.status AS favorite_status,
                                pr.ratings AS patron_rating -- Fetch the logged-in patron's rating
                            FROM books b
                            LEFT JOIN author a ON b.author_id = a.author_id -- Join to get the author's name
                            LEFT JOIN category c ON b.category_id = c.category_id -- Join to get the category name
                            LEFT JOIN ratings r ON b.book_id = r.book_id
                            LEFT JOIN borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id -- Join to get the borrow status specific to the patron
                            LEFT JOIN favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id -- Join to get the favorite status specific to the patron
                            LEFT JOIN ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id"; // Join to get the patron's rating

                    // Add the category filter to the query if it exists
                    if ($categoryFilter) {
                        $query .= " WHERE c.category LIKE :categoryFilter";
                    }

                    // Add GROUP BY clause
                    $query .= " GROUP BY b.book_id, b.category_id, c.category, b.title, b.image, b.author_id, a.author, br.status, f.status, pr.ratings";

                    // Prepare the PDO statement
                    $stmt = $pdo->prepare($query);

                    // Bind the category filter with wildcards if it exists
                    if ($categoryFilter) {
                        $stmt->bindValue(':categoryFilter', '%' . $categoryFilter . '%', PDO::PARAM_STR);
                    }

                    // Bind the patron's ID if it is set
                    if ($patrons_id !== null) {
                        $stmt->bindValue(':patrons_id', $patrons_id, PDO::PARAM_INT);
                    } else {
                        // Handle the case where patron ID is not set
                        // Optionally, you can redirect or show an error message here
                        echo "Patron ID is not set. Please log in.";
                        exit; // Stop further execution if patron ID is not set
                    }

                    // Execute the statement
                    $stmt->execute();

                    // Fetch books
                    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    ?>



                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($categoryFilter); ?>"
                    </div>

                    <div class="container-search row">
                        <input type="text" class="search">

                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">


                    <div class="result-contents">

                        <div class="row-contents-center" id="bookContainer">
                            <?php if ($books): ?>
                                <?php foreach ($books as $book): ?>
                                    <div class="container-books-2">

                                        <div class="books-id"><?php echo htmlspecialchars($book['book_id']); ?></div>

                                        <div class="books-image-2">
                                            <img src="../book_images/<?= htmlspecialchars($book['image']) ?>" class="image">
                                        </div>

                                        <div class="books-category"><?php echo htmlspecialchars($book['category_name']); ?></div>

                                        <div class="books-borrow-status"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                                        <div class="books-favorite"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                        <div class="books-ratings"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                        <div class="books-user-ratings"><?php echo htmlspecialchars($book['patron_rating']); ?></div>

                                        <div class="books-name"><?= htmlspecialchars($book['title']) ?></div>
                                        <div class="books-author"><?= htmlspecialchars($book['author']) ?></div>
                                    </div>


                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-results">No books found for the selected category.</div>
                            <?php endif; ?>
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
<!-- <script src="js/book-list-pagination.js"></script> -->

<script src="js/close-status.js"></script>
<script src="js/tooltips.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookContainer = document.getElementById('bookContainer');
        const itemsPerPageSelect = document.getElementById('itemsPerPage');
        const prevPageButton = document.getElementById('prevPage');
        const nextPageButton = document.getElementById('nextPage');
        const pageInfo = document.getElementById('pageInfo');
        const searchInput = document.querySelector('.search');

        let itemsPerPage = parseInt(itemsPerPageSelect.value) || 20;
        let currentPage = 1;
        let lastClickedBook = null;

        // Store original book data
        let originalBooks = Array.from(bookContainer.children).map(bookDiv => ({
            id: bookDiv.querySelector('.books-id').textContent,
            category: bookDiv.querySelector('.books-category').textContent,
            borrowStatus: bookDiv.querySelector('.books-borrow-status').textContent,
            favoriteStatus: bookDiv.querySelector('.books-favorite').textContent,
            avgRating: bookDiv.querySelector('.books-ratings').textContent,
            patronRating: bookDiv.querySelector('.books-user-ratings').textContent,
            name: bookDiv.querySelector('.books-name') ? bookDiv.querySelector('.books-name').textContent : '',
            image: bookDiv.querySelector('.books-image-2 img') ? bookDiv.querySelector('.books-image-2 img').src : 'book-image-placeholder.png',
            author: bookDiv.querySelector('.books-author') ? bookDiv.querySelector('.books-author').textContent : ''
        }));
        let filteredBooks = [...originalBooks];

        function addBookClickListeners() {
            const books = document.querySelectorAll('.container-books-2');

            books.forEach(book => {
                book.addEventListener('click', () => {
                    lastClickedBook = book;

                    const bookId = book.querySelector('.books-id').textContent;
                    const bookCategory = book.querySelector('.books-category').textContent;
                    const bookBorrowStatus = book.querySelector('.books-borrow-status').textContent;
                    const bookFavorite = book.querySelector('.books-favorite').textContent;

                    const bookTitle = book.querySelector('.books-name-2').textContent;
                    const bookImage = book.querySelector('.books-image-2 img').src;
                    const bookAuthor = book.querySelector('.books-author').textContent || 'Unknown Author';
                    const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';
                    const bookUserRating = book.querySelector('.books-user-ratings') ? book.querySelector('.books-user-ratings').textContent : '0';


                    const bookDetailsContainer = document.getElementById('book-details');
                    bookDetailsContainer.querySelector('.books-contents-id').textContent = bookId;
                    bookDetailsContainer.querySelector('.books-contents-category').textContent = bookCategory;
                    bookDetailsContainer.querySelector('.books-contents-borrow-status').textContent = bookBorrowStatus;
                    bookDetailsContainer.querySelector('.books-contents-favorite').textContent = bookFavorite;

                    bookDetailsContainer.querySelector('.books-contents-name').textContent = bookTitle;
                    bookDetailsContainer.querySelector('.books-contents-image').innerHTML = `<img src="${bookImage}" class="image">`;
                    bookDetailsContainer.querySelector('.books-contents-author').textContent = bookAuthor;
                    bookDetailsContainer.querySelector('.books-contents-ratings').textContent = bookRating;
                    bookDetailsContainer.querySelector('.books-contents-user-ratings').textContent = bookUserRating;

                    bookDetailsContainer.querySelector('.ratings-number').textContent = bookRating;

                    bookDetailsContainer.style.display = 'flex';
                    bookDetailsContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'end'
                    });



                    // Check if bookCategory is not equal to 'Circulation Section'
                    if (bookCategory.toLowerCase() !== 'circulation'.toLowerCase()) {
                        const borrowButton = bookDetailsContainer.querySelector('.button-borrow');
                        const tooltip = bookDetailsContainer.querySelector('.tooltiptexts');

                        if (borrowButton) {
                            borrowButton.disabled = true;
                        }

                        if (tooltip) {
                            tooltip.style.display = 'flex';
                        }
                    } else {
                        const borrowButton = bookDetailsContainer.querySelector('.button-borrow');
                        const tooltip = bookDetailsContainer.querySelector('.tooltiptexts');

                        if (bookBorrowStatus.toLowerCase() === 'pending') {
                            if (borrowButton) {
                                borrowButton.disabled = true;
                                tooltip.textContent = 'You have already requested to borrow this book. You can now claim it at the library';
                            }

                            if (tooltip) {
                                tooltip.style.display = 'flex';
                            }

                        } else if (bookBorrowStatus.toLowerCase() === 'borrowed') {
                            if (borrowButton) {
                                borrowButton.disabled = true;
                                tooltip.textContent = 'You are still borrowing the book. Please return it on time.';

                            }

                            if (tooltip) {
                                tooltip.style.display = 'flex';
                            }

                        } else {
                            if (borrowButton) {
                                borrowButton.disabled = false;
                                borrowButton.textContent = 'Borrow';
                            }

                            if (tooltip) {
                                tooltip.style.display = 'none';
                            }
                        }

                    }




                    const favoriteButton = bookDetailsContainer.querySelector('.button-bookmark');
                    const favoriteButtonRed = bookDetailsContainer.querySelector('.button-bookmark-red');

                    const tooltipAdd = bookDetailsContainer.querySelector('#tooltip-add');
                    const tooltipRemove = bookDetailsContainer.querySelector('#tooltip-remove');


                    if (bookFavorite !== '' && bookFavorite !== 'Remove') {
                        favoriteButton.style.display = 'none';
                        favoriteButtonRed.style.display = 'flex';
                        tooltipAdd.style.display = 'none';
                        tooltipRemove.style.display = 'flex';
                    } else {
                        favoriteButton.style.display = 'flex';
                        favoriteButtonRed.style.display = 'none';
                        tooltipAdd.style.display = 'flex';
                        tooltipRemove.style.display = 'none';
                    }





                    const ratingButton = bookDetailsContainer.querySelector('.button-ratings');
                    const ratingButtonYellow = bookDetailsContainer.querySelector('.button-ratings-yellow');

                    const tooltipAddRatings = bookDetailsContainer.querySelector('#tooltip-add-ratings');
                    const tooltipUpdateRatings = bookDetailsContainer.querySelector('#tooltip-update-ratings');


                    if (bookUserRating !== '') {
                        ratingButton.style.display = 'none';
                        ratingButtonYellow.style.display = 'flex';
                        tooltipAddRatings.style.display = 'none';
                        tooltipUpdateRatings.style.display = 'flex';
                    } else {
                        ratingButton.style.display = 'flex';
                        ratingButtonYellow.style.display = 'none';
                        tooltipAddRatings.style.display = 'flex';
                        tooltipUpdateRatings.style.display = 'none';
                    }




                    const stars = document.querySelectorAll('.star');
                    let rating = parseFloat(bookRating);

                    if (!isNaN(rating)) {
                        rating = Math.round(rating);

                        stars.forEach(star => {
                            const value = parseFloat(star.getAttribute('data-value'));
                            if (value <= rating) {
                                star.classList.add('active');
                            } else {
                                star.classList.remove('active');
                            }
                        });
                    }
                });
            });

            const closeButton = document.querySelector('.button-close');
            closeButton.addEventListener('click', () => {
                document.getElementById('book-details').style.display = 'none';

                if (lastClickedBook) {
                    lastClickedBook.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        }

        function updateDisplay() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedBooks = filteredBooks.slice(start, end);

            bookContainer.innerHTML = '';

            if (paginatedBooks.length === 0) {
                bookContainer.innerHTML = `
                <div class="container-unavailable">
                    <div class="unavailable-image">
                        <img src="../images/no-books.png" class="image">
                    </div>
                    <div class="unavailable-text">Not Found</div>
                </div>
            `;
            } else {
                paginatedBooks.forEach(book => {
                    const bookDiv = document.createElement('div');
                    bookDiv.classList.add('container-books-2');
                    bookDiv.innerHTML = `
                    <div class="books-id">${book.id}</div>

                    <div class="books-image-2">
                        <img src="${book.image}" class="image">
                    </div>
                    <div class="books-category">${book.category}</div>
                    <div class="books-borrow-status">${book.borrowStatus}</div>
                    <div class="books-favorite">${book.favoriteStatus}</div>
                    <div class="books-ratings">${book.avgRating}</div>
                    <div class="books-user-ratings">${book.patronRating}</div>

                    <div class="books-name-2">${book.name}</div>
                    <div class="books-author" style="display: none;">${book.author}</div>
                `;
                    bookContainer.appendChild(bookDiv);
                });
            }

            const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevPageButton.disabled = currentPage === 1;
            nextPageButton.disabled = currentPage === totalPages;

            addBookClickListeners(); // Add the click listeners to the newly displayed books
        }

        itemsPerPageSelect.addEventListener('change', function() {
            itemsPerPage = parseInt(this.value) || 20;
            currentPage = 1;
            updateDisplay();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        prevPageButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateDisplay();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        nextPageButton.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateDisplay();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            if (query) {
                filteredBooks = originalBooks.filter(book => book.name.toLowerCase().includes(query));
            } else {
                filteredBooks = [...originalBooks];
            }

            currentPage = 1;
            updateDisplay();
        });

        updateDisplay(); // Initial display update with listeners attached
    });
</script>




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