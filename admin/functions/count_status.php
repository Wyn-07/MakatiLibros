<?php
include '../connection.php';

try {
    // SQL query to count books, patrons, borrowed today, and returned today
    $sql = "
        SELECT 
            (SELECT COUNT(*) FROM books) AS total_books,
            (SELECT COUNT(*) FROM patrons) AS total_patrons,
            (SELECT COUNT(*) FROM borrow WHERE status = 'Borrowed' AND DATE(borrow_date) = CURDATE()) AS borrowed_today,
            (SELECT COUNT(*) FROM borrow WHERE status = 'Returned' AND DATE(return_date) = CURDATE()) AS returned_today
    ";
    
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
