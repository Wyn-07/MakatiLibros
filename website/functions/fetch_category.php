<?php

$sql = "SELECT category FROM category"; 
$stmt = $pdo->query($sql);

$category = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cat = trim($row['category']);
        
        if (!in_array($cat, $category)) {
            $category[] = $cat;
        }
    }
}

?>
