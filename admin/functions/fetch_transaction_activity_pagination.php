<?php
require '../../connection.php';

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'logs_id';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['logs_id', 'date_time', 'page', 'manage', 'librarians_id'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'logs_id';
}

$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT 
            l.logs_id,         
            l.date_time, 
            l.old_data,
            l.new_data,
            l.page,
            l.manage,
            CONCAT(lib.firstname, ' ', lib.lastname) AS librarian_name
        FROM 
            logs l
        JOIN 
            librarians lib ON l.librarians_id = lib.librarians_id
        WHERE
            l.page = 'Transaction' AND 
            (l.logs_id LIKE :searchParam OR
            l.date_time LIKE :searchParam OR    /* Added date_time to search */
            l.old_data LIKE :searchParam OR
            l.new_data LIKE :searchParam OR
            l.page LIKE :searchParam OR
            l.manage LIKE :searchParam OR
            CONCAT(lib.firstname, ' ', lib.lastname) LIKE :searchParam)
        ORDER BY $sortColumn $sortOrder
        LIMIT :offset, :itemsPerPage";

$stmt = $pdo->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();

$logsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuery = "SELECT COUNT(*) 
               FROM logs l
               JOIN librarians lib ON l.librarians_id = lib.librarians_id
               WHERE 
                   l.page = 'Transaction' AND
                   (l.logs_id LIKE :searchParam OR
                   l.date_time LIKE :searchParam OR    /* Added date_time to total count */
                   l.old_data LIKE :searchParam OR
                   l.new_data LIKE :searchParam OR
                   l.page LIKE :searchParam OR
                   l.manage LIKE :searchParam OR
                   CONCAT(lib.firstname, ' ', lib.lastname) LIKE :searchParam)";

$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$totalStmt->execute();
$totalLogs = $totalStmt->fetchColumn();

echo json_encode([
    'logsList' => $logsList,
    'totalLogs' => $totalLogs
]);
?>
