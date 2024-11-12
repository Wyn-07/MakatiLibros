<?php
function getReturnedBooks($pdo) {
    // Prepare the SQL query
    $query = "SELECT 
              b.borrow_id,         
              b.borrow_date,
              b.borrow_time,
              b.return_date,
              b.return_time,
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
              b.status = 'Returned'
          ORDER BY 
              b.borrow_date DESC"; 
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $returnedBooks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $patronsName = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']);

        $returnedBooks[] = [
            'borrow_id' => $row['borrow_id'],  
            'borrow_date' => $row['borrow_date'],
            'borrow_time' => $row['borrow_time'],
            'return_date' => $row['return_date'],
            'return_time' => $row['return_time'],
            'patrons_id' => $row['patrons_id'],
            'patrons_name' => $patronsName,   
            'book_id' => $row['book_id'],      
            'title' => $row['title'],
            'acc_number' => $row['acc_number'],     
            'class_number' => $row['class_number'], 
            'status' => $row['status']
        ];
    }
    return $returnedBooks;
}
?>
