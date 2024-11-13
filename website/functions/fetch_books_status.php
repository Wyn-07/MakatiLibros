<?php

$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

if ($status && $patrons_id) {
    // Prepare the SQL query to fetch books based on status and patron ID
    $query = $pdo->prepare("
        SELECT 
            b.book_id,
            b.title,
            b.image,
            b.copyright,
            a.author AS author_name,          
            c.category AS category_name,      
            br.borrow_id,
            br.patrons_id,
            br.borrow_date,
            br.borrow_time,
            br.return_date,
            br.return_time,
            IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,  -- Average rating
            MAX(CASE WHEN r.patrons_id = :patrons_id THEN r.ratings ELSE NULL END) AS user_rating,  -- User's specific rating
            br.status AS borrow_status,
            f.status AS favorite_status,
            CASE 
                WHEN br2.borrow_id IS NOT NULL AND br2.status != 'Returned' THEN 'Unavailable' 
                ELSE 'Available' 
            END AS book_status
        FROM books b
        LEFT JOIN author a ON b.author_id = a.author_id
        LEFT JOIN category c ON b.category_id = c.category_id
        LEFT JOIN borrow br ON br.book_id = b.book_id AND br.patrons_id = :patrons_id
        LEFT JOIN ratings r ON r.book_id = b.book_id
        LEFT JOIN favorites f ON f.book_id = b.book_id AND f.patrons_id = :patrons_id
        LEFT JOIN borrow br2 ON br2.book_id = b.book_id AND br2.status != 'Returned'
        WHERE br.status = :status
        GROUP BY b.book_id, br.borrow_id
    ");
    $query->bindParam(':status', $status);
    $query->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    // Generate the HTML to display books
    if (count($result) > 0) {
        foreach ($result as $row) {
            $formattedDateReturn = date("F j, Y", strtotime($row['return_date']));
            $formattedTimeReturn = date("h:i A", strtotime($row['return_time']));
            $dayOfWeekReturn = date("l", strtotime($row['return_date']));


            $formattedDate = date("F j, Y", strtotime($row['borrow_date']));
            $formattedTime = date("h:i A", strtotime($row['borrow_time']));
            $dayOfWeek = date("l", strtotime($row['borrow_date']));


            $avgRating = floatval($row['avg_rating']); // Get the average rating

?>

            <div class="profile-container-white-filter-content">


                <div class="row row-between">
                    <div>
                        <div class="date-font">Return Date</div>
                        <div class="profile-row">
                            <div class="books-contents-date formatted-date-return"><?= $formattedDateReturn ?></div>
                            <div class="books-contents-date formatted-time-return"><?= $formattedTimeReturn ?></div>
                            <div class="books-contents-date day-of-week-return"><?= $dayOfWeekReturn ?></div>
                        </div>
                    </div>


                    <div>
                        
                    </div>
                </div>

                <hr style="margin: 15px 0">

                <div class="container-books-contents-full">

                    <div class="books-contents-image-profile">
                        <img src="../book_images/<?= htmlspecialchars($row['image']) ?>" alt="Book Image" class="image">
                    </div>

                    <div class="books-contents">

                        <div class="books-contents-name"><?= htmlspecialchars($row['title']) ?></div>
                        <div class="books-contents-author"><?= htmlspecialchars($row['author_name']) ?></div>
                        <div class="books-contents-copyright"><?= htmlspecialchars($row['copyright']) ?></div>

                        <div class="books-category" style="display: none"><?= htmlspecialchars($row['category_name']) ?></div>
                        <div class="patrons-id" style="display: none"><?= htmlspecialchars($row['patrons_id']) ?></div>
                        <div class="borrow-id" style="display: none"><?= htmlspecialchars($row['borrow_id']) ?></div>
                        <div class="books-contents-id" style="display: none"> <?= htmlspecialchars($row['book_id']) ?></div>
                        <div class="books-status" style="display: none"> <?= htmlspecialchars($row['book_status']) ?></div>
                        <div class="books-avg-ratings" style="display: none"> <?= htmlspecialchars($row['avg_rating']) ?></div>
                        <div class="books-borrow-status" style="display: none"> <?= htmlspecialchars($row['borrow_status']) ?></div>
                        <div class="books-favorite" style="display: none"> <?= htmlspecialchars($row['favorite_status']) ?></div>
                        <div class="books-user-ratings" style="display: none"> <?= htmlspecialchars($row['user_rating']) ?></div>

                        <div class="row">
                            <div class="star-rating">
                                <?php
                                // Loop through and apply active color for each star
                                for ($i = 1; $i <= 5; $i++) {
                                    $starClass = ($i <= $avgRating) ? 'active' : ''; // Add 'active' class for rated stars
                                    echo "<span class='star $starClass' data-value='$i'>&#9733;</span>";
                                }
                                ?>
                            </div>

                            <div class="ratings-description">
                                <div class="ratings-number"><?= htmlspecialchars($row['avg_rating']) ?></div>&nbspout of 5
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
                                <div class="button button-ratings" onclick="openRateModal(<?= htmlspecialchars($row['book_id']) ?>, <?= htmlspecialchars($row['patrons_id']) ?>, <?= htmlspecialchars($row['user_rating']) ?>)"><img src="../images/star-white.png" alt=""></div>
                                <span class='tooltiptexts'>Add ratings</span>
                            </div>

                            <div class="tooltipss" id="tooltip-update-ratings">
                                <button class="button button-ratings-yellow" onclick="openRateModal(<?= htmlspecialchars($row['book_id']) ?>, <?= htmlspecialchars($row['patrons_id']) ?>, <?= htmlspecialchars($row['user_rating']) ?>)"><img src="../images/star-white.png" alt=""></button>
                                <span class='tooltiptexts'>Update ratings</span>
                            </div>

                        </div>

                        <?php include 'modal/add_rating_modal_transaction.php'; ?>


                    </div>

                </div>


                <hr style="margin: 15px 0">

                <div class="row row-between">
                    <div>
                        
                    </div>


                    <div>
                        <div class="date-font">Borrow Date</div>
                        <div class="profile-row">
                            <div class="books-contents-date formatted-date"><?= $formattedDate ?></div>
                            <div class="books-contents-date formatted-time"><?= $formattedTime ?></div>
                            <div class="books-contents-date day-of-week"><?= $dayOfWeek ?></div>
                        </div>
                    </div>
                </div>

            </div>




<?php
        }
    } else {
        echo '<div class="profile-container-white-filter-content" style="min-height: 300px; justify-content:center; align-items: center">
                    <div class="unavailable-image">
                        <img src="../images/no-books.png" class="image">
                    </div>
                    <div class="unavailable-text">None</div>
                </div>';
    }
} else {
    echo "<div>Error: Status or Patron ID not set.</div>";
}
?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loop through all .books-contents elements
        const books = document.querySelectorAll('.books-contents');

        books.forEach(book => {
            const bookStatus = book.querySelector('.books-status').textContent;
            const bookCategory = book.querySelector('.books-category').textContent;
            const bookBorrowStatus = book.querySelector('.books-borrow-status').textContent;
            const bookFavorite = book.querySelector('.books-favorite').textContent;
            const bookUserRating = book.querySelector('.books-user-ratings').textContent;

            // Buttons and tooltips
            const borrowButton = book.querySelector('.button-borrow');
            const tooltipBorrow = book.querySelector('.tooltiptexts');

            const favoriteButton = book.querySelector('.button-bookmark');
            const favoriteButtonRed = book.querySelector('.button-bookmark-red');
            const tooltipAdd = book.querySelector('#tooltip-add');
            const tooltipRemove = book.querySelector('#tooltip-remove');

            const ratingButton = book.querySelector('.button-ratings');
            const ratingButtonYellow = book.querySelector('.button-ratings-yellow');
            const tooltipAddRatings = book.querySelector('#tooltip-add-ratings');
            const tooltipUpdateRatings = book.querySelector('#tooltip-update-ratings');

            // Borrow Button Logic
            if (bookStatus === 'Unavailable' && bookCategory.toLowerCase() === 'circulation' && bookBorrowStatus.toLowerCase() === '') {
                borrowButton.disabled = true;
                tooltipBorrow.textContent = 'Unavailable to borrow because it has been borrowed by someone else.';
                tooltipBorrow.style.display = 'flex';
            } else if (bookCategory.toLowerCase() === 'circulation' && bookBorrowStatus.toLowerCase() === 'pending') {
                borrowButton.disabled = true;
                tooltipBorrow.textContent = 'You have already requested to borrow this book. You can now claim it at the library.';
                tooltipBorrow.style.display = 'flex';
            } else if (bookCategory.toLowerCase() === 'circulation' && bookBorrowStatus.toLowerCase() === 'borrowing') {
                borrowButton.disabled = true;
                tooltipBorrow.textContent = 'You are still borrowing the book. Please return it on time.';
                tooltipBorrow.style.display = 'flex';
            } else if (bookStatus === 'Available' && bookCategory.toLowerCase() !== 'circulation' && bookBorrowStatus.toLowerCase() === '') {
                borrowButton.disabled = true;
                tooltipBorrow.textContent = 'Only books from the Circulation Section can be borrowed, but you can still read this book in the library.';
                tooltipBorrow.style.display = 'flex';
            } else {
                borrowButton.disabled = false;
                tooltipBorrow.style.display = 'none';
            }

            // Favorite Button Logic
            if (bookFavorite === 'Added') {
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


            // Rating Button Logic
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
        });
    });
</script>


<script src="js/tooltips.js"></script>