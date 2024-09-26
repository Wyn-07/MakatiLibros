<?php
function getMissingList($pdo) {
    $query = "
        SELECT 
            m.missing_id,                         
            m.copyright, 
            m.category_id, 
            c.category AS category_name,        
            m.title, 
            m.author_id, 
            a.author AS author_name,            
            m.acc_number,                      
            m.class_number                     
        FROM 
            missing m
        JOIN 
            category c ON m.category_id = c.category_id  
        JOIN 
            author a ON m.author_id = a.author_id       
        ORDER BY 
            m.copyright DESC
    ";
    

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    

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
            'class_number' => $row['class_number']      
        ];
    }
    
    return $missingList;
}
?>
