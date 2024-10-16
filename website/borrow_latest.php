<?php

$patrons_id = isset($_SESSION['patrons_id']) ? $_SESSION['patrons_id'] : null;

$pythonScript = 'borrow_cbf_tfidf.py';

// Get the book IDs for content-based filtering (CBF)
$book_cbf_id_json = shell_exec("py $pythonScript " . $patrons_id);

// Decode the JSON output
$book_cbf_id = json_decode($book_cbf_id_json, true);

// Initialize an empty array for books
$books_borrow_cbf = [];

if ($book_cbf_id && count($book_cbf_id) > 0) {
    // Create a comma-separated string from the book IDs
    $book_ids_str = implode(',', array_map('intval', $book_cbf_id));

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
            FIELD(b.book_id, $book_ids_str)  
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);

    $stmt->execute();

    $books_borrow_cbf = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<div class="contents-big-padding">
    <div class="row row-between">
        <div>Based on your latest borrow book</div>
        <div class="button button-view-more" data-category="Category 1">View More</div>
    </div>
    <div class="row-books-container">
        <div class="arrow-left">
            <div class="arrow-image">
                <img src="../images/prev-black.png" alt="" class="image">
            </div>
        </div>
        <div class="row-books">
            <?php if ($books_borrow_cbf && count($books_borrow_cbf) > 0): ?>
                <?php foreach ($books_borrow_cbf as $book_borrow): ?>
                    <div class="container-books">
                        <div class="books-id" style="display: none;">
                            <?php echo htmlspecialchars($book_borrow['book_id']); ?>
                        </div>

                        <div class="books-image">
                            <img src="../book_images/<?php echo htmlspecialchars($book_borrow['image']); ?>" class="image" alt="Book Image" loading="lazy">
                        </div>

                        <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book_borrow['category']); ?></div>
                        <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book_borrow['borrow_status']); ?></div>
                        <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book_borrow['favorite_status']); ?></div>
                        <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book_borrow['avg_rating']); ?></div>
                        <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book_borrow['patron_rating']); ?></div>

                        <div class="books-name"><?php echo htmlspecialchars($book_borrow['title']); ?></div>
                        <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book_borrow['author']); ?></div>
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