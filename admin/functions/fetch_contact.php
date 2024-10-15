<?php
function getContact($pdo) {
    // Prepare the SQL query
   $query = "SELECT 
              contact_id,
              title,
              contact,
              description,
              image
          FROM 
              contact";

    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $contactData = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $contactData[] = [
            'contact_id' => $row['contact_id'],  
            'title' => $row['title'], 
            'contact' => $row['contact'], 
            'description' => $row['description'], 
            'image' => $row['image']  
        ];
    }
    return $contactData;
}
?>
