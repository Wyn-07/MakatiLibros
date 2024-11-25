<?php
function getBorrowingBooks($pdo) {
    // Prepare the SQL query
    $query = "
        SELECT 
            b.borrow_id,         
            b.borrow_date,
            b.status, 
            u.patrons_id,        
            u.firstname, 
            u.middlename,        
            u.lastname, 
            u.suffix,           
            bk.book_id,         
            bk.title, 
            bk.acc_number,         
            bk.class_number
        FROM 
            borrow b 
        JOIN 
            patrons u ON b.patrons_id = u.patrons_id 
        JOIN 
            books bk ON b.book_id = bk.book_id
        WHERE 
            b.status = 'Borrowing'              -- Filter for status 'borrowing'
        ORDER BY 
            b.borrow_date DESC
    "; 

    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $borrowingBooks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Construct patrons_name with included fields
        $patronsName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']);
        
        $borrowingBooks[] = [
            'borrow_id' => $row['borrow_id'],  
            'borrow_date' => $row['borrow_date'],
            'patrons_id' => $row['patrons_id'], 
            'patrons_name' => $patronsName,     
            'book_id' => $row['book_id'],       
            'title' => $row['title'],
            'acc_number' => $row['acc_number'],     
            'class_number' => $row['class_number'], 
            'status' => $row['status']
        ];
    }
    return $borrowingBooks;
}
?>
