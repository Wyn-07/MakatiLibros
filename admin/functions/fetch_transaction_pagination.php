<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'borrow_id';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'borrow_id'; // Default to 'borrow_id' if invalid
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT 
            b.borrow_id,         
            IF(b.borrow_date != 'Pending' AND b.borrow_time != 'Pending', 
               DATE_FORMAT(STR_TO_DATE(CONCAT(b.borrow_date, ' ', b.borrow_time), '%m/%d/%Y %H:%i:%s'), '%c/%e/%Y %H:%i:%s'), 
               'Pending') AS borrow_datetime,
            IF(b.return_date != 'Pending' AND b.return_time != 'Pending', 
               DATE_FORMAT(STR_TO_DATE(CONCAT(b.return_date, ' ', b.return_time), '%m/%d/%Y %H:%i:%s'), '%c/%e/%Y %H:%i:%s'), 
               'Pending') AS return_datetime,
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
            (b.borrow_id LIKE :searchParam OR
            (b.borrow_date != 'Pending' AND b.borrow_time != 'Pending' AND 
                DATE_FORMAT(STR_TO_DATE(CONCAT(b.borrow_date, ' ', b.borrow_time), '%m/%d/%Y %H:%i:%s'), '%c/%e/%Y %H:%i:%s') LIKE :searchParam) OR
            (b.return_date != 'Pending' AND b.return_time != 'Pending' AND 
                DATE_FORMAT(STR_TO_DATE(CONCAT(b.return_date, ' ', b.return_time), '%m/%d/%Y %H:%i:%s'), '%c/%e/%Y %H:%i:%s') LIKE :searchParam) OR
            bk.title LIKE :searchParam OR
            CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE :searchParam OR
            b.status LIKE :searchParam)";

// Adjust sorting to use the selected sort column and order
$sql .= " ORDER BY ";

switch ($sortColumn) {
    case 'borrow_id':
        $sql .= 'b.borrow_id ' . $sortOrder;
        break;
    case 'borrow_datetime':
        $sql .= 'CASE 
                    WHEN b.borrow_date != "Pending" THEN STR_TO_DATE(CONCAT(b.borrow_date, " ", b.borrow_time), "%m/%d/%Y %H:%i:%s")
                    ELSE NULL 
                END ' . $sortOrder;
        break;
    case 'return_datetime':
        $sql .= 'CASE 
                    WHEN b.return_date != "Pending" THEN STR_TO_DATE(CONCAT(b.return_date, " ", b.return_time), "%m/%d/%Y %H:%i:%s")
                    ELSE NULL 
                END ' . $sortOrder;
        break;
    case 'status':
        $sql .= 'b.status ' . $sortOrder;
        break;
    case 'title':
        $sql .= 'LOWER(TRIM(bk.title)) ' . $sortOrder; // Apply case-insensitive and trimmed sorting to title
        break;
    case 'patrons_name':
        $sql .= 'CONCAT(u.firstname, " ", u.middlename, " ", u.lastname) ' . $sortOrder;
        break;
    default:
        $sql .= 'b.borrow_id ' . $sortOrder;  // Default sorting by borrow_id if invalid column
}

// Add pagination
$sql .= " LIMIT :offset, :itemsPerPage";

$stmt = $pdo->prepare($sql);

// Bind search parameters for specified columns
$searchParam = "%$search%";
$stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);

// Bind pagination parameters
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch borrow records
$transactionList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total borrow records for pagination
$totalQuery = "SELECT COUNT(*) 
               FROM borrow b 
               JOIN patrons u ON b.patrons_id = u.patrons_id 
               JOIN books bk ON b.book_id = bk.book_id
               WHERE 
                   b.borrow_id LIKE :searchParam OR
                   (b.borrow_date != 'Pending' AND b.borrow_time != 'Pending' AND CONCAT(b.borrow_date, ' ', b.borrow_time) LIKE :searchParam) OR
                   (b.return_date != 'Pending' AND b.return_time != 'Pending' AND CONCAT(b.return_date, ' ', b.return_time) LIKE :searchParam) OR
                   bk.title LIKE :searchParam OR
                   CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) LIKE :searchParam OR
                   b.status LIKE :searchParam";
$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalTransaction = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'transactionList' => $transactionList,
    'totalTransaction' => $totalTransaction
]);
?>
