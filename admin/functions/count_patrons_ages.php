<?php
include '../../connection.php';

// SQL query to count patrons in different age groups
$sql = "SELECT 
            SUM(CASE WHEN age < 13 THEN 1 ELSE 0 END) AS Child,
            SUM(CASE WHEN age >= 13 AND age < 20 THEN 1 ELSE 0 END) AS Teenager,
            SUM(CASE WHEN age >= 20 AND age < 60 THEN 1 ELSE 0 END) AS Adult,
            SUM(CASE WHEN age >= 60 THEN 1 ELSE 0 END) AS Senior
        FROM patrons";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the data in JSON format
echo json_encode($result);
?>
