<?php
function getMissingList($pdo) {
    // Prepare the SQL query
    $query = "
        SELECT 
            m.missing_id,                         -- Fetch the missing entry ID
            b.copyright,                          -- Fetch the copyright from the books table
            b.category_id,                        
            c.category AS category_name,          -- Fetch the category name
            b.title,                              -- Fetch the title from the books table
            b.author_id,                          
            a.author AS author_name,              -- Fetch the author name
            b.acc_number,                         -- Fetch acc_number
            b.class_number,                       -- Fetch class_number
            b.image                               -- Fetch the book image
        FROM 
            missing m
        JOIN 
            books b ON m.book_id = b.book_id      -- Join with books table
        JOIN 
            category c ON b.category_id = c.category_id  -- Join with the category table
        JOIN 
            author a ON b.author_id = a.author_id        -- Join with the author table
    ";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $missingList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $missingList[] = [
            'missing_id' => $row['missing_id'],               
            'copyright' => $row['copyright'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'],   
            'title' => $row['title'],
            'author_id' => $row['author_id'],
            'author_name' => $row['author_name'],      
            'acc_number' => $row['acc_number'],         
            'class_number' => $row['class_number'],
            'image' => $row['image']         
        ];
    }
    
    return $missingList;
}
?>
