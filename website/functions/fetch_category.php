<?php

$sql = "SELECT category, description FROM category"; 
$stmt = $pdo->query($sql);

$categories = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cat = trim($row['category']);
        $description = trim($row['description']);
        
        // Ensure the category is not already in the array
        if (!isset($categories[$cat])) {
            $categories[$cat] = $description;
        }
    }
}

?>
