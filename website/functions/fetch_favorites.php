<?php
if (!isset($_SESSION['patrons_id'])) {
    die('User not logged in.');
}

$patrons_id = $_SESSION['patrons_id'];

try {
    $sql = "
                SELECT *
                FROM (
                    SELECT f.favorite_id, b.book_id, b.title, a.author AS author_name, c.category AS category_name, 
                        b.image, b.copyright, 
                        f.status AS favorite_status, 
                        br.status AS borrow_status, 
                        IFNULL(ROUND(AVG(r.ratings), 2), 0) AS avg_rating,
                        MAX(CASE WHEN r.patrons_id = ? THEN r.ratings ELSE NULL END) AS user_rating,
                        ROW_NUMBER() OVER (PARTITION BY b.book_id ORDER BY f.favorite_id) AS row_num
                    FROM favorites f
                    JOIN books b ON f.book_id = b.book_id
                    JOIN author a ON b.author_id = a.author_id
                    JOIN category c ON b.category_id = c.category_id
                    LEFT JOIN borrow br ON b.book_id = br.book_id AND br.patrons_id = ?
                    LEFT JOIN ratings r ON b.book_id = r.book_id
                    WHERE f.patrons_id = ? AND f.status = 'Added'
                    GROUP BY f.favorite_id, b.book_id, b.title, a.author, c.category, b.image, b.copyright, br.status, f.status
                ) subquery
                WHERE row_num = 1
            ";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $patrons_id, PDO::PARAM_INT); 
    $stmt->bindParam(2, $patrons_id, PDO::PARAM_INT); 
    $stmt->bindParam(3, $patrons_id, PDO::PARAM_INT);

    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
