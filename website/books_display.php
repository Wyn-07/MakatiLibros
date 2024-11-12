<div class="arrow-left">
    <div class="arrow-image">
        <img src="../images/prev-black.png" alt="" class="image">
    </div>
</div>
<div class="row-books">
    <?php if (isset($recommend_books) && count($recommend_books) > 0): ?>
        <?php foreach ($recommend_books as $book): ?>
            <div class="container-books">
                <div class="books-id" style="display: none;"><?php echo htmlspecialchars($book['book_id']); ?></div>
                <div class="books-image">
                    <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" alt="Book Image" loading="lazy">
                </div>
                <div class="books-status" style="display: none;"><?php echo htmlspecialchars($book['book_status']); ?></div>
                <div class="books-category" style="display: none;"><?php echo htmlspecialchars($book['category']); ?></div>
                <div class="books-borrow-status" style="display: none;"><?php echo htmlspecialchars($book['borrow_status']); ?></div>
                <div class="books-favorite" style="display: none;"><?php echo htmlspecialchars($book['favorite_status']); ?></div>
                <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                <div class="books-user-ratings" style="display: none;"><?php echo htmlspecialchars($book['patron_rating']); ?></div>
                <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['author']); ?></div>
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
