<!-- top rated -->
<?php

try {
    // Prepare the SQL query
    $sql = "
        SELECT 
            b.book_id, 
            b.title,
            b.copyright, 
            a.author, 
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
            borrow br2 ON b.book_id = br2.book_id 
        LEFT JOIN 
            condemned cd ON b.book_id = cd.book_id -- Left join with condemned table
        LEFT JOIN 
            missing ms ON b.book_id = ms.book_id -- Left join with missing table
        WHERE 
            cd.book_id IS NULL AND ms.book_id IS NULL -- Exclude books in condemned or missing
        GROUP BY 
            b.book_id
        ORDER BY 
            avg_rating DESC
        LIMIT $number;
    ";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the results as an associative array
    $books_top_rated = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>