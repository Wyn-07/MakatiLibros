<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortBookColumn = isset($_GET['sortBookColumn']) ? $_GET['sortBookColumn'] : 'book_id';
$sortBookOrder = isset($_GET['sortBookOrder']) && $_GET['sortBookOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['book_id','acc_number', 'class_number', 'title', 'editions', 'author_name', 'category_name', 'copyright', 'copies'];
if (!in_array($sortBookColumn, $validColumns)) {
    $sortBookColumn = 'book_id'; // Default
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query
$query = "
    SELECT 
        bk.book_id,                         
        bk.copyright, 
        bk.category_id,
        bk.copies,
        bk.editions, 
        c.category AS category_name,        
        bk.title, 
        bk.author_id, 
        a.author AS author_name,            
        bk.acc_number,                      
        bk.class_number,                    
        bk.image
    FROM 
        books bk
    JOIN 
        category c ON bk.category_id = c.category_id  
    JOIN 
        author a ON bk.author_id = a.author_id        
    LEFT JOIN 
        condemned cd ON bk.book_id = cd.book_id       
    LEFT JOIN 
        missing ms ON bk.book_id = ms.book_id         
    WHERE 
        cd.book_id IS NULL AND ms.book_id IS NULL
        AND (
            bk.book_id LIKE ? OR
            bk.title LIKE ? OR
            a.author LIKE ? OR
            c.category LIKE ? OR
            bk.copies LIKE ? OR
            bk.editions LIKE ? OR
            bk.acc_number LIKE ? OR
            bk.class_number LIKE ?
        )
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
$stmt->bindParam(8, $searchParam, PDO::PARAM_STR);

// Pagination parameters
$stmt->bindParam(9, $offset, PDO::PARAM_INT);
$stmt->bindParam(10, $itemsPerPage, PDO::PARAM_INT);


$stmt->execute();

// Fetch book records
$bookList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total book records for pagination
$totalQuery = "
    SELECT COUNT(*) 
    FROM 
        books bk
    JOIN 
        category c ON bk.category_id = c.category_id  
    JOIN 
        author a ON bk.author_id = a.author_id        
    LEFT JOIN 
        condemned cd ON bk.book_id = cd.book_id       
    LEFT JOIN 
        missing ms ON bk.book_id = ms.book_id         
    WHERE 
        cd.book_id IS NULL AND ms.book_id IS NULL
        AND (
            bk.book_id LIKE ? OR
            bk.title LIKE ? OR
            a.author LIKE ? OR
            c.category LIKE ? OR
            bk.copies LIKE ? OR
            bk.editions LIKE ? OR
            bk.acc_number LIKE ? OR
            bk.class_number LIKE ?
        )
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
$totalStmt->bindParam(8, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalBooks = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'bookList' => $bookList,
    'totalBooks' => $totalBooks
]);
?>
