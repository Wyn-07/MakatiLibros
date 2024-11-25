<!-- Most borrowed books -->
<?php
try {
    // SQL query to get the most borrowed books across all users
    $sql = "
        SELECT 
            b.book_id, 
            b.title, 
            b.copyright,
            a.author, 
            c.category AS category_name, 
            b.image,
            IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating, 
            COUNT(br_all.borrow_id) AS borrow_count,
            br.status AS borrow_status,  
            f.status AS favorite_status, 
            pr.ratings AS patron_rating,
            CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM borrow br2 
                    WHERE br2.book_id = b.book_id 
                    AND br2.status != 'Returned'
                ) THEN 'Unavailable' 
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
            borrow br_all ON b.book_id = br_all.book_id  
        LEFT JOIN 
            borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id 
        LEFT JOIN 
            favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id 
        LEFT JOIN 
            ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id 
        GROUP BY 
            b.book_id
        HAVING 
            borrow_count > 0
        ORDER BY 
            borrow_count DESC
        LIMIT :number;
    ";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $stmt->bindParam(':number', $number, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the results as an associative array
    $books_most_borrowed = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
