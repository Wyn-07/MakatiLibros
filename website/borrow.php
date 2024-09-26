<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php
session_start();
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

                    <div class="contents-title">
                        Borrowed Books
                    </div>

                    <div class="container-search row">
                        <input type="text" id="search" class="search" placeholder="Search by title or author">

                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>


                </div>

                <div class="container-content">
                    <div id="search-results" class="contents-big-padding">

                        <?php
                        if (!isset($_SESSION['patrons_id'])) {
                            die('User not logged in.');
                        }

                        $patrons_id = $_SESSION['patrons_id'];

                        try {
                            $sql = "
                                    SELECT 
                                        b.book_id,
                                        b.title,
                                        b.image,
                                        a.author AS author_name,         -- Get author name
                                        c.category AS category_name,     -- Get category name
                                        br.borrow_date,
                                        IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,
                                        MAX(CASE WHEN r.patrons_id = ? THEN r.ratings ELSE NULL END) AS user_rating,
                                        br.status AS borrow_status,
                                        f.status AS favorite_status
                                    FROM 
                                        borrow br
                                    JOIN 
                                        books b ON br.book_id = b.book_id
                                    JOIN 
                                        author a ON b.author_id = a.author_id      -- Join with author table
                                    JOIN 
                                        category c ON b.category_id = c.category_id  -- Join with category table
                                    LEFT JOIN 
                                        ratings r ON b.book_id = r.book_id
                                    LEFT JOIN 
                                        favorites f ON b.book_id = f.book_id AND f.patrons_id = ?
                                    WHERE 
                                        br.patrons_id = ?
                                    GROUP BY 
                                        b.book_id, br.borrow_date, br.status, f.status, a.author, c.category
                                    ORDER BY 
                                        br.borrow_date DESC
                                ";

                            $stmt = $pdo->prepare($sql);

                            $stmt->bindParam(1, $patrons_id, PDO::PARAM_INT); // Bind patrons_id for user_rating
                            $stmt->bindParam(2, $patrons_id, PDO::PARAM_INT); // Bind patrons_id for favorite status
                            $stmt->bindParam(3, $patrons_id, PDO::PARAM_INT); // Bind patrons_id for borrow status

                            $stmt->execute();

                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $groupedResults = [];
                            foreach ($result as $row) {
                                $groupedResults[$row['borrow_date']][] = $row;
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>




                        <div class="contents">
                            <?php foreach ($groupedResults as $date => $books): ?>
                                <div class="row row-between">
                                    <div><?php echo htmlspecialchars($date); ?></div>

                                </div>

                                <div class="row-books-container">
                                    <div class="arrow-left">
                                        <div class="arrow-image">
                                            <img src="../images/prev-black.png" alt="" class="image">
                                        </div>
                                    </div>

                                    <div class="row-books">
                                        <?php foreach ($books as $book): ?>
                                            <div class="container-books">
                                                <div class="books-id" style="display: none;"><?php echo htmlspecialchars($book['book_id']); ?></div>


                                                <div class="books-image">
                                                    <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image">
                                                </div>

                                                <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category_name']); ?></div>
                                                <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                                                <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                                                <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                                <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['user_rating']); ?></div>

                                                <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                                <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author_name']); ?></div>

                                            </div>

                                            <!-- Hidden form for borrowing books -->
                                            <form id="borrowForm" action="functions/borrow_books.php" method="POST" style="display: none;">
                                                <input type="hidden" name="book_id" id="bookIdInput">
                                                <input type="hidden" name="patrons_id" id="userIdInput">
                                                <input type="hidden" name="status" value="Pending">
                                                <input type="hidden" name="borrow_date" value="">
                                                <input type="hidden" name="return_date" value="">
                                                <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                            </form>


                                            <!-- Hidden form for add favorite books -->
                                            <form id="addFavoriteForm" action="functions/add_favorite.php" method="POST" style="display: none;">
                                                <input type="hidden" name="add_book_id" id="addBookIdInput">
                                                <input type="hidden" name="add_patrons_id" id="addUserIdInput">
                                                <input type="hidden" name="status" id="statusInput" value="Added">
                                                <input type="hidden" name="referer" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                            </form>

                                            <!-- Hidden form for remove favorite books -->
                                            <form id="removeFavoriteForm" action="functions/remove_favorite.php" method="POST" style="display: none;">
                                                <input type="hidden" name="remove_book_id" id="removeBookIdInput">
                                                <input type="hidden" name="remove_patrons_id" id="removeUserIdInput">
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
                            <?php endforeach; ?>
                        </div>


                        <div id="not-found-message" class="container-unavailable" style="display: none;">
                            <div class="unavailable-image">
                                <img src="../images/no-books.png" class="image">
                            </div>
                            <div class="unavailable-text">Not Found</div>
                        </div>


                    </div>
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
<script src="js/book-with-date-filter.js"></script>

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
                const userId = <?php echo json_encode($patrons_id); ?>;

                if (bookId && userId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('bookIdInput').value = bookId;
                    document.getElementById('userIdInput').value = userId;

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
                const addUserId = <?php echo json_encode($patrons_id); ?>;

                if (addBookId && addUserId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('addBookIdInput').value = addBookId;
                    document.getElementById('addUserIdInput').value = addUserId;

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
                const removeUserId = <?php echo json_encode($patrons_id); ?>;

                if (removeBookId && removeUserId) {
                    // Populate the hidden form fields with book and user data
                    document.getElementById('removeBookIdInput').value = removeBookId;
                    document.getElementById('removeUserIdInput').value = removeUserId;

                    // Submit the form
                    document.getElementById('removeFavoriteForm').submit();
                }
            });
        }
    });
</script>