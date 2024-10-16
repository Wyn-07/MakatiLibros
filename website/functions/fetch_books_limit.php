<?php

// Query to fetch the first 20 books for each category based on book_id
$sql = "SELECT 
                b.category_id,
                c.category AS category_name,
                b.title,
                b.image,
                b.book_id,
                b.author_id,
                a.author,
                IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,
                br.status AS borrow_status,
                f.status AS favorite_status,
                pr.ratings AS patron_rating
            FROM (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY category_id ORDER BY book_id) AS rn
                FROM books
            ) AS b
            LEFT JOIN author a ON b.author_id = a.author_id
            LEFT JOIN category c ON b.category_id = c.category_id
            LEFT JOIN ratings r ON b.book_id = r.book_id
            LEFT JOIN borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id
            LEFT JOIN favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id
            LEFT JOIN ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id
            LEFT JOIN condemned cd ON b.book_id = cd.book_id
            LEFT JOIN missing ms ON b.book_id = ms.book_id
            WHERE cd.book_id IS NULL AND ms.book_id IS NULL AND rn <= 20
            GROUP BY b.book_id, b.category_id, c.category, b.title, b.image, b.author_id, a.author, br.status, f.status, pr.ratings
            ORDER BY b.category_id, b.book_id
            ";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT); 
$stmt->execute();


// Fetch all results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$books_limit = [];
foreach ($result as $row) {
    // Skip rows where category_id is empty
    if (!empty($row['category_id'])) {
        $books_limit[$row['category_id']][] = [
            'book_id' => $row['book_id'],
            'title' => $row['title'],
            'image' => $row['image'],
            'author' => $row['author'], 
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'], 
            'avg_rating' => number_format($row['avg_rating'], 1),
            'borrow_status' => $row['borrow_status'],
            'favorite_status' => $row['favorite_status'],
            'patron_rating' => $row['patron_rating'] 
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

// Remove duplicate titles within each category_id
foreach ($books_limit as $category_id => $bookDetails) {
    $books_limit[$category_id] = removeDuplicates($bookDetails);
}

// Remove category_id with no books
$books_limit = array_filter($books_limit, function ($bookDetails) {
    return !empty($bookDetails);
});
