<?php
function getCategoryName($pdo) {
    // Prepare the SQL query
    $query = "SELECT category FROM category";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $categoryName = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoryName[] = $row['category'];
    }
    return $categoryName;
}
?>
