<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortBookColumn = isset($_GET['sortBookColumn']) ? $_GET['sortBookColumn'] : 'book_id';
$sortBookOrder = isset($_GET['sortBookOrder']) && $_GET['sortBookOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['book_id', 'copyright', 'category_name', 'title', 'author_name', 'acc_number', 'class_number'];
if (!in_array($sortBookColumn, $validColumns)) {
    $sortBookColumn = 'book_id'; // Default
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query for missing books
$query = "
    SELECT 
        m.missing_id,                         -- Fetch the missing entry ID
        b.copyright,                          -- Fetch the copyright from the books table
        b.category_id,                        
        c.category AS category_name,          -- Fetch the category name
        b.title,                              -- Fetch the title from the books table
        b.author_id,                          
        a.author AS author_name,              -- Fetch the author name
        b.acc_number,                         -- Fetch acc_number
        b.class_number,                       -- Fetch class_number
        b.image                               -- Fetch the book image
    FROM 
        missing m
    JOIN 
        books b ON m.book_id = b.book_id     
    JOIN 
        category c ON b.category_id = c.category_id 
    JOIN 
        author a ON b.author_id = a.author_id        
    WHERE 
        b.book_id LIKE ? OR
        b.title LIKE ? OR
        a.author LIKE ? OR
        c.category LIKE ? OR
        b.acc_number LIKE ? OR
        b.class_number LIKE ? OR
        b.copyright LIKE ? 
"; 

// Add sorting
$query .= " ORDER BY $sortBookColumn $sortBookOrder";

// Add pagination
$query .= " LIMIT ?, ?";

// Prepare and execute SQL query
$stmt = $pdo->prepare($query);

// Bind search parameters for specified columns
$searchParam = "%$search%";
$stmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(4, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(5, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(6, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(7, $searchParam, PDO::PARAM_STR);

// Bind pagination parameters
$stmt->bindParam(8, $offset, PDO::PARAM_INT);
$stmt->bindParam(9, $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch book records for missing books
$missingBookList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total missing book records for pagination
$totalQuery = "
    SELECT COUNT(*) 
    FROM 
        missing m
    JOIN 
        books b ON m.book_id = b.book_id      
    JOIN 
        category c ON b.category_id = c.category_id  
    JOIN 
        author a ON b.author_id = a.author_id        
    WHERE 
        b.book_id LIKE ? OR
        b.title LIKE ? OR
        a.author LIKE ? OR
        c.category LIKE ? OR
        b.acc_number LIKE ? OR
        b.class_number LIKE ? OR
        b.copyright LIKE ?
"; 

$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(4, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(5, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(6, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(7, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalMissingBooks = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'missingBookList' => $missingBookList,
    'totalMissingBooks' => $totalMissingBooks
]);
?>
