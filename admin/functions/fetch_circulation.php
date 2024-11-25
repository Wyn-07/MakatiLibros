<?php
function getCirculation($pdo) {
    // Prepare the SQL query
    $query = "
        SELECT 
            bk.book_id,                         -- Fetch the book ID
            bk.copyright, 
            bk.category_id, 
            c.category AS category_name,        -- Fetch the category name
            bk.title, 
            bk.author_id, 
            a.author AS author_name,            -- Fetch the author name
            bk.acc_number,                      -- Fetch acc_number
            bk.class_number,                    -- Fetch class_number
            bk.image
        FROM 
            books bk
        JOIN 
            category c ON bk.category_id = c.category_id  -- Join with the category table
        JOIN 
            author a ON bk.author_id = a.author_id        -- Join with the author table
        LEFT JOIN 
            condemned cd ON bk.book_id = cd.book_id       -- Left join with condemned table
        LEFT JOIN 
            missing ms ON bk.book_id = ms.book_id         -- Left join with missing table
        WHERE 
            cd.book_id IS NULL 
            AND ms.book_id IS NULL 
            AND c.category = 'Circulation'               -- Filter for category name 'Circulation'
    ";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $bookCirculation = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookCirculation[] = [
            'book_id' => $row['book_id'],             
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
    
    return $bookCirculation;
}
?>
