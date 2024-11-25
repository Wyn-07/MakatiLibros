<?php

include "../../connection.php";

// Get the book_id from the POST request
$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : null;
$patrons_id = isset($_POST['patrons_id']) ? $_POST['patrons_id'] : null;

if ($book_id) {
    // Fetch book details from the database
    $stmt = $pdo->prepare("
        SELECT b.book_id, b.title, b.image, c.category, 
               a.author, b.copyright, 
               b.copies,
               IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,
               (SELECT br.status FROM borrow br WHERE br.book_id = b.book_id AND br.patrons_id = :patrons_id ORDER BY br.borrow_date DESC LIMIT 1) AS borrow_status,
               (SELECT status FROM favorites WHERE book_id = b.book_id ORDER BY date DESC LIMIT 1) AS favorite_status,
               (SELECT ratings FROM ratings  WHERE book_id = b.book_id AND patrons_id = :patrons_id LIMIT 1) AS user_rating,
               COUNT(br.borrow_id) AS borrow_count,
               b.copies - (
                   SELECT COUNT(*) 
                   FROM borrow br2 
                   WHERE br2.book_id = b.book_id 
                     AND br2.status != 'Returned'
               ) AS available_copies
        FROM books b
        LEFT JOIN category c ON b.category_id = c.category_id
        LEFT JOIN author a ON b.author_id = a.author_id
        LEFT JOIN ratings r ON b.book_id = r.book_id
        LEFT JOIN borrow br ON b.book_id = br.book_id
        WHERE b.book_id = :book_id
        GROUP BY b.book_id, b.title, b.image, c.category, a.author, b.copyright, b.copies
    ");

    $stmt->execute(['book_id' => $book_id, 'patrons_id' => $patrons_id]);

    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the book details as a JSON response
    if ($book) {
        // Calculate available copies and determine statuses
        $availableCopies = max((int) $book['available_copies'], 0); // Ensure no negative values
        $totalCopies = (int) $book['copies'];

        $bookStatus = $availableCopies > 0 ? "Available" : "Unavailable";
        $bookStatusMessage = "Available $availableCopies out of $totalCopies copies";

        echo json_encode([
            'bookId' => $book['book_id'],
            'bookTitle' => $book['title'],
            'bookImage' => $book['image'],
            'bookCategory' => $book['category'],
            'author' => $book['author'],
            'copyright' => $book['copyright'],
            'avgRating' => $book['avg_rating'],
            'borrowStatus' => $book['borrow_status'] ? $book['borrow_status'] : '',
            'favoriteStatus' => $book['favorite_status'] ? $book['favorite_status'] : '',
            'userRating' => $book['user_rating'] ? $book['user_rating'] : '',
            'borrowCount' => $book['borrow_count'],
            'bookStatus' => $bookStatus,
            'bookStatusMessage' => $bookStatusMessage
        ]);
    } else {
        echo json_encode(['error' => 'Book not found']);
    }
}
