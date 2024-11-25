<?php
function getOfficials($pdo) {
    // Prepare the SQL query
   $query = "SELECT 
              officials_id,
              name,
              title,
              image
          FROM 
              officials";

    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $officials = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $officials[] = [
            'officials_id' => $row['officials_id'],  
            'name' => $row['name'],
            'title' => $row['title'], 
            'image' => $row['image']  
        ];
    }
    return $officials;
}
?>
