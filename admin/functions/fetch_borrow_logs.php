<?php
function getBorrowLogs($pdo)
{
    // Modify the query to join borrow_logs with category and books tables
    $query = "
        SELECT bl.log_id, bl.log_date, 
               c.category_id AS category_id, 
               c.category AS category, 
               b.book_id AS book_id, 
               b.title AS book_title, 
               bl.firstname, 
               bl.middlename, 
               bl.lastname, 
               bl.suffix, 
               bl.age, 
               bl.gender, 
               bl.barangay, 
               bl.city 
        FROM borrow_logs bl
        JOIN category c ON bl.category_id = c.category_id
        JOIN books b ON bl.book_id = b.book_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $borrowLogs = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $borrowLogs[] = [
            'log_id' => $row['log_id'],
            'log_date' => $row['log_date'],
            'category_id' => $row['category_id'], // Include category_id
            'category' => $row['category'],
            'book_id' => $row['book_id'], // Include book_id
            'book_title' => $row['book_title'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'lastname' => $row['lastname'],
            'suffix' => $row['suffix'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'barangay' => $row['barangay'],
            'city' => $row['city']
        ];
    }
    return $borrowLogs;
}
?>
