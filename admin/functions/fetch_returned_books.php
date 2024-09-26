<?php
function getReturnedBooks($pdo) {
    // Prepare the SQL query
    $query = "SELECT 
                  b.return_date, 
                  u.firstname, 
                  u.lastname, 
                  bk.title, 
                  bk.acc_number,         -- Fetch acc_number
                  bk.class_number        -- Fetch class_number
              FROM 
                  borrow b 
              JOIN 
                  patrons u ON b.patrons_id = u.patrons_id 
              JOIN 
                  books bk ON b.book_id = bk.book_id
              WHERE 
                  b.return_date IS NOT NULL AND b.return_date != ''
              ORDER BY 
                  STR_TO_DATE(b.return_date, '%M %d, %Y') DESC";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $returnedBooks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $returnedBooks[] = [
            'return_date' => $row['return_date'],
            'name' => $row['firstname'] . ' ' . $row['lastname'],
            'title' => $row['title'],
            'acc_number' => $row['acc_number'],     // Include acc_number
            'class_number' => $row['class_number']  // Include class_number
        ];
    }
    return $returnedBooks;
}
?>
