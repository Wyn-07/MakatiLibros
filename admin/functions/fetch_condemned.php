<?php
function getCondemnedList($pdo) {
    $query = "
        SELECT 
            c.condemned_id,                         
            c.copyright, 
            c.category_id, 
            cat.category AS category_name,        
            c.title, 
            c.author_id, 
            a.author AS author_name,            
            c.acc_number,                      
            c.class_number                     
        FROM 
            condemned c
        JOIN 
            category cat ON c.category_id = cat.category_id  
        JOIN 
            author a ON c.author_id = a.author_id       
        ORDER BY 
            c.copyright DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $condemnedList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $condemnedList[] = [
            'condemned_id' => $row['condemned_id'],               
            'copyright' => $row['copyright'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'],   
            'title' => $row['title'],
            'author_id' => $row['author_id'],
            'author_name' => $row['author_name'],      
            'acc_number' => $row['acc_number'],         
            'class_number' => $row['class_number']      
        ];
    }
    
    return $condemnedList;
}
?>
