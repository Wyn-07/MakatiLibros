<?php
function getAuthorName($pdo) {
    // Prepare the SQL query
    $query = "SELECT author FROM author";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $authorName = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $authorName[] = $row['author'];
    }
    return $authorName;
}
?>
