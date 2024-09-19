<?php

// Query to fetch book category names, titles, images, author names, book_id, average ratings, borrow status, favorite status, and the patron's rating
$sql = "SELECT b.category_id, c.category AS category_name, b.title, b.image, b.book_id, b.author_id, 
               a.author, -- Fetch the author's name
               IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating,
               br.status AS borrow_status, -- Fetch the borrow status specific to the patron
               f.status AS favorite_status,
               pr.ratings AS patron_rating -- Fetch the logged-in patron's rating
        FROM books b
        LEFT JOIN author a ON b.author_id = a.author_id -- Join to get the author's name
        LEFT JOIN category c ON b.category_id = c.category_id -- Join to get the category name
        LEFT JOIN ratings r ON b.book_id = r.book_id
        LEFT JOIN borrow br ON b.book_id = br.book_id AND br.patrons_id = :patrons_id -- Join to get the borrow status specific to the patron
        LEFT JOIN favorites f ON b.book_id = f.book_id AND f.patrons_id = :patrons_id -- Join to get the favorite status specific to the patron
        LEFT JOIN ratings pr ON b.book_id = pr.book_id AND pr.patrons_id = :patrons_id -- Join to get the patron's rating
        GROUP BY b.book_id, b.category_id, c.category, b.title, b.image, b.author_id, a.author, br.status, f.status, pr.ratings";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT); 
$stmt->execute();

// Fetch all results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$books = [];
foreach ($result as $row) {
    // Skip rows where category_id is empty
    if (!empty($row['category_id'])) {
        $books[$row['category_id']][] = [
            'book_id' => $row['book_id'],
            'title' => $row['title'],
            'image' => $row['image'],
            'author' => $row['author'], 
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'], 
            'avg_rating' => number_format($row['avg_rating'], 1),
            'borrow_status' => $row['borrow_status'], // Changed from 'status' to 'borrow_status'
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
foreach ($books as $category_id => $bookDetails) {
    $books[$category_id] = removeDuplicates($bookDetails);
}

// Remove category_id with no books
$books = array_filter($books, function ($bookDetails) {
    return !empty($bookDetails);
});

?>
