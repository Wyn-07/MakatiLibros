<?php

// Fetch the user's interests
if (!empty($patrons_id)) {
    $interestQuery = "SELECT interest FROM patrons WHERE patrons_id = :patrons_id";
    $interestStmt = $pdo->prepare($interestQuery);
    $interestStmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $interestStmt->execute();
    $userInterest = $interestStmt->fetchColumn();
} else {
    $userInterest = null;
}

// Determine the SQL query based on the presence of patron ID and user interest
if (!empty($patrons_id) && !empty($userInterest)) {
    $sql = "SELECT 
            b.book_id,
            b.title,
            b.copies,
            b.category_id,
            c.category AS category_name,
            b.image,
            COUNT(br_all.borrow_id) AS borrow_count,
            NULL AS copyright, 
            NULL AS author,    
            NULL AS avg_rating, 
            CASE 
                WHEN b.copies > (
                    SELECT COUNT(*) 
                    FROM borrow br2 
                    WHERE br2.book_id = b.book_id 
                    AND br2.status != 'Returned'
                ) THEN 'Available'
                ELSE 'Unavailable'
            END AS book_status
        FROM (
            SELECT * ,
                ROW_NUMBER() OVER (PARTITION BY category_id ORDER BY book_id) AS rn
            FROM books
        ) AS b
        LEFT JOIN 
            category c ON b.category_id = c.category_id
        LEFT JOIN 
            borrow br_all ON b.book_id = br_all.book_id  
        LEFT JOIN 
            borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id 
        WHERE 
            rn <= 10 
            AND FIND_IN_SET(c.category, :user_interest) > 0  -- Filter by user's interest
        GROUP BY 
            b.book_id, b.category_id, c.category, b.title, b.image, b.copies
        ORDER BY 
            b.category_id, b.book_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
$stmt->bindParam(':user_interest', $userInterest, PDO::PARAM_STR);

} else {
    // Fetch all categories if no patron ID or interest
    $sql = "SELECT 
                b.category_id,
                c.category AS category_name,
                b.copies,
                b.title,
                b.copyright,
                b.image,
                b.book_id,
                b.author_id,
                a.author,
                IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,
                CASE 
                    WHEN br2.borrow_id IS NOT NULL AND br2.status != 'Returned' THEN 'Unavailable' 
                    ELSE 'Available' 
                END AS book_status
            FROM (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY category_id ORDER BY book_id) AS rn
                FROM books
            ) AS b
            LEFT JOIN 
                author a ON b.author_id = a.author_id
            LEFT JOIN 
                category c ON b.category_id = c.category_id
            LEFT JOIN 
                ratings r ON b.book_id = r.book_id
            LEFT JOIN 
                borrow br2 ON b.book_id = br2.book_id  
            LEFT JOIN 
                condemned cd ON b.book_id = cd.book_id
            LEFT JOIN 
                missing ms ON b.book_id = ms.book_id
            WHERE 
                cd.book_id IS NULL 
                AND ms.book_id IS NULL 
                AND rn <= 10  -- Limit books per category
            GROUP BY 
                b.book_id, b.category_id, c.category, b.title, b.copyright, b.image, b.author_id, a.author
            ORDER BY 
                b.category_id, b.book_id";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();

// Fetch all results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process results as before
$books_limit = [];
foreach ($result as $row) {
    if (!empty($row['category_id'])) {
        $books_limit[$row['category_id']][] = [
            'book_id' => $row['book_id'],
            'title' => $row['title'],
            'copies' => $row['copies'],
            'copyright' => $row['copyright'],
            'image' => $row['image'],
            'author' => $row['author'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'],
            'avg_rating' => number_format($row['avg_rating'], 1),
            'book_status' => $row['book_status']
        ];
    }
}

// Function to remove duplicates based on 'title'
function removeDuplicates($array)
{
    $unique = [];
    $titles = [];

    foreach ($array as $item) {
        if (!in_array($item['title'], $titles)) {
            $unique[] = $item;
            $titles[] = $item['title'];
        }
    }

    return $unique;
}

// Remove duplicates and filter empty categories as before
foreach ($books_limit as $category_id => $bookDetails) {
    $books_limit[$category_id] = removeDuplicates($bookDetails);
}

$books_limit = array_filter($books_limit, function ($bookDetails) {
    return !empty($bookDetails);
});
?>
