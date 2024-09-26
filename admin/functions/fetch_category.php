<?php
function getCategoryList($pdo) {
    $query = "SELECT category_id, category FROM category ORDER BY category ASC";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $categoryList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoryList[] = [
            'category_id' => $row['category_id'],
            'category' => $row['category']
        ];
    }
    return $categoryList;
}
?>
