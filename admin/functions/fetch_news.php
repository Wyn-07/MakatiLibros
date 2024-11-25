<?php
function getNews($pdo) {
    // Prepare the SQL query
   $query = "SELECT 
              news_id,
              title,
              date,
              description,
              image
          FROM 
              news";

    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $news = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $news[] = [
            'news_id' => $row['news_id'],  
            'title' => $row['title'], 
            'date' => $row['date'], 
            'description' => $row['description'], 
            'image' => $row['image']  
        ];
    }
    return $news;
}
?>
