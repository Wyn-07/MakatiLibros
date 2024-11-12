<?php
require '../../connection.php'; 

date_default_timezone_set('Asia/Manila');

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortDelinquentColumn = isset($_GET['sortDelinquentColumn']) ? $_GET['sortDelinquentColumn'] : 'borrow_id';
$sortDelinquentOrder = isset($_GET['sortDelinquentOrder']) && $_GET['sortDelinquentOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['patrons_name', 'borrow_date', 'title'];
if (!in_array($sortDelinquentColumn, $validColumns)) {
    $sortDelinquentColumn = 'borrow_id'; // Default
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query
$sql = "SELECT 
            b.borrow_id,         
            b.borrow_date,
            b.borrow_time,
            b.return_date,
            b.return_time,
            b.status, 
            CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) AS patrons_name,
            bk.title  
        FROM 
            borrow b 
        JOIN 
            patrons u ON b.patrons_id = u.patrons_id 
        JOIN 
            books bk ON b.book_id = bk.book_id
        WHERE
            (b.borrow_id LIKE ? OR
            b.borrow_date LIKE ? OR
            b.return_date LIKE ? OR
            bk.title LIKE ? OR
            CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE ? OR
            b.status LIKE ?)
            AND b.status = 'Borrowing'
            AND STR_TO_DATE(b.borrow_date, '%m/%d/%Y') < CURDATE() - INTERVAL 5 DAY";
; 

// Add sorting
$sql .= " ORDER BY $sortDelinquentColumn $sortDelinquentOrder";

// Add pagination
$sql .= " LIMIT ?, ?";

// Prepare and execute SQL query
$stmt = $pdo->prepare($sql);

// Bind search parameters for specified columns
$searchParam = "%$search%";
$stmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(4, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(5, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(6, $searchParam, PDO::PARAM_STR);

// Bind pagination parameters
$stmt->bindParam(7, $offset, PDO::PARAM_INT);
$stmt->bindParam(8, $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch borrow records
$delinquentList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total borrow records for pagination
$totalQuery = "SELECT COUNT(*) 
               FROM borrow b 
               JOIN patrons u ON b.patrons_id = u.patrons_id 
               JOIN books bk ON b.book_id = bk.book_id
               WHERE 
                   (b.borrow_id LIKE ? OR
                   b.borrow_date LIKE ? OR
                   b.return_date LIKE ? OR
                   bk.title LIKE ? OR
                   CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE ? OR
                   b.status LIKE ?)
                   AND b.status = 'Borrowing'
                   AND STR_TO_DATE(b.borrow_date, '%m/%d/%Y') < CURDATE() - INTERVAL 5 DAY";

$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(4, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(5, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(6, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalDelinquent = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'delinquentList' => $delinquentList,
    'totalDelinquent' => $totalDelinquent
]);
?>
