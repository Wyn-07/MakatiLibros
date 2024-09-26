<?php
function getBorrowedBooks($pdo) {
    // Prepare the SQL query
    $query = "SELECT 
                  b.borrow_id,         -- Include borrow_id
                  b.borrow_date, 
                  u.patrons_id,        -- Include patrons_id
                  u.firstname, 
                  u.middlename,        -- Include middlename
                  u.lastname, 
                  u.suffix,            -- Include suffix
                  bk.book_id,          -- Include book_id
                  bk.title, 
                  bk.acc_number,         
                  bk.class_number,       
                  b.return_date 
              FROM 
                  borrow b 
              JOIN 
                  patrons u ON b.patrons_id = u.patrons_id 
              JOIN 
                  books bk ON b.book_id = bk.book_id
              ORDER BY 
                  STR_TO_DATE(b.borrow_date, '%M %d, %Y') DESC";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $borrowedBooks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Construct patrons_name with included fields
        $patronsName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']);
        
        $borrowedBooks[] = [
            'borrow_id' => $row['borrow_id'],  // Include borrow_id in the result
            'borrow_date' => $row['borrow_date'],
            'patrons_id' => $row['patrons_id'], // Include patrons_id
            'patrons_name' => $patronsName,     // Set patrons_name
            'book_id' => $row['book_id'],       // Include book_id
            'title' => $row['title'],
            'acc_number' => $row['acc_number'],     
            'class_number' => $row['class_number'], 
            'status' => empty($row['return_date']) ? 'Borrowed' : 'Returned'
        ];
    }
    return $borrowedBooks;
}
?>