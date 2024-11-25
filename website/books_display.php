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
                <div class="patrons-id" style="display: none;"><?php echo $patrons_id  ?></div>

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