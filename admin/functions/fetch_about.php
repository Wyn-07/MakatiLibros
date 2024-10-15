<?php
function getAbout($pdo) {
    // Prepare the SQL query
    $query = "
        SELECT 
            about_id,
            mission,
            mission_image_1,
            mission_image_2,
            mission_image_3,
            vision,
            vision_image_1,
            vision_image_2,
            vision_image_3,
            history
        FROM 
            about
        WHERE
            about_id = 1;
        
    ";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $about = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $about;
}


?>




