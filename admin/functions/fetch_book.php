<?php
function getBookList($pdo) {
    // Prepare the SQL query
    $query = "
        SELECT 
            bk.copyright, 
            bk.category_id, 
            c.category AS category_name,      -- Fetch the category name
            bk.title, 
            bk.author_id, 
            a.author AS author_name,          -- Fetch the author name
            bk.acc_number,                    -- Fetch acc_number
            bk.class_number                   -- Fetch class_number
        FROM 
            books bk
        JOIN 
            category c ON bk.category_id = c.category_id  -- Join with the category table
        JOIN 
            author a ON bk.author_id = a.author_id        -- Join with the author table
        ORDER BY 
            bk.copyright DESC
    ";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $bookList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookList[] = [
            'copyright' => $row['copyright'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'], // Add category name
            'title' => $row['title'],
            'author_id' => $row['author_id'],
            'author_name' => $row['author_name'],      // Add author name
            'acc_number' => $row['acc_number'],        // Add acc_number
            'class_number' => $row['class_number']     // Add class_number
        ];
    }
    
    return $bookList;
}



function getBookTitles($pdo) {
    $query = "SELECT title FROM books WHERE category_id = '2' ORDER BY title ASC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $bookTitles = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookTitles[] = $row['title'];
    }
    return $bookTitles;
}



?>




