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
$validColumns = ['patrons_name', 'borrow_date', 'title', 'status'];
if (!in_array($sortDelinquentColumn, $validColumns)) {
    $sortDelinquentColumn = 'borrow_id'; // Default
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query to fetch only the required columns
$sql = "SELECT 
            CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) AS patrons_name,
            b.borrow_date,
            b.borrow_time,
            bk.title,
            d.status,
            d.delinquent_id
        FROM 
            delinquent d
        JOIN 
            borrow b ON d.borrow_id = b.borrow_id
        JOIN 
            patrons u ON b.patrons_id = u.patrons_id 
        JOIN 
            books bk ON b.book_id = bk.book_id
        WHERE
            (CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE ? OR
            b.borrow_date LIKE ? OR
            bk.title LIKE ? OR
            d.status LIKE ?)"; 

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

// Bind pagination parameters
$stmt->bindParam(5, $offset, PDO::PARAM_INT);
$stmt->bindParam(6, $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch delinquent records
$delinquentList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total delinquent records for pagination
$totalQuery = "SELECT COUNT(*) 
               FROM delinquent d
               JOIN borrow b ON d.borrow_id = b.borrow_id
               JOIN patrons u ON b.patrons_id = u.patrons_id 
               JOIN books bk ON b.book_id = bk.book_id
               WHERE 
                   (CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE ? OR
                   b.borrow_date LIKE ? OR
                   bk.title LIKE ? OR
                   d.status LIKE ?)";

$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(4, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalDelinquent = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'delinquentList' => $delinquentList,
    'totalDelinquent' => $totalDelinquent
]);
?>
