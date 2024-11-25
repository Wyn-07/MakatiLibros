<?php
include '../../connection.php'; // Import the $pdo variable

// Get the search query if it exists
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query with placeholders
$query = "SELECT 
              category_id, 
              category 
          FROM 
              category 
          WHERE 
              category LIKE :search 
          ORDER BY 
              category ASC";

// Prepare and execute the statement with the search term
$stmt = $pdo->prepare($query);
$searchTerm = "%" . $search . "%";
$stmt->execute([':search' => $searchTerm]);

$categoryList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($categoryList);
?>
