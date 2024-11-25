<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'author';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// SQL query with search and pagination, and dynamic sorting
$sql = "SELECT * FROM author WHERE author LIKE ? ORDER BY $sortColumn $sortOrder LIMIT ?, ?";
$stmt = $pdo->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(2, $offset, PDO::PARAM_INT);
$stmt->bindParam(3, $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();

// Fetch authors
$authorList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total authors for pagination
$totalQuery = "SELECT COUNT(*) FROM author WHERE author LIKE ?";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->execute();
$totalAuthors = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'authorList' => $authorList,
    'totalAuthors' => $totalAuthors
]);
?>
