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
            COUNT(r.rating_id) AS ratings_count,
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
        JOIN 
            patrons p ON FIND_IN_SET(c.category, p.interest) > 0
        WHERE 
            cd.book_id IS NULL 
            AND ms.book_id IS NULL -- Exclude books in condemned or missing
            AND p.patrons_id = :patrons_id 
        GROUP BY 
            b.book_id
        HAVING 
            ratings_count > 0
        ORDER BY 
            ratings_count DESC
        LIMIT :number;  -- Use a parameterized limit
    ";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $stmt->bindParam(':number', $number, PDO::PARAM_INT);  // Bind the limit parameter
    $stmt->execute();

    // Fetch the results as an associative array
    $books_most_rated_user_interest = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>