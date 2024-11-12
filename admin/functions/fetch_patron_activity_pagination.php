<?php
require '../../connection.php';

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPatronPerPage = isset($_GET['itemsPatronPerPage']) ? (int)$_GET['itemsPatronPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortPatronColumn = isset($_GET['sortPatronColumn']) ? $_GET['sortPatronColumn'] : 'logs_id';
$sortPatronOrder = isset($_GET['sortPatronOrder']) && $_GET['sortPatronOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['logs_id', 'date_time', 'page', 'manage', 'librarians_id'];
if (!in_array($sortPatronColumn, $validColumns)) {
    $sortPatronColumn = 'logs_id';
}

$offset = ($page - 1) * $itemsPatronPerPage;

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
            l.page = 'Book' AND 
            (l.logs_id LIKE :searchParam OR
            l.date_time LIKE :searchParam OR    /* Added date_time to search */
            l.old_data LIKE :searchParam OR
            l.new_data LIKE :searchParam OR
            l.page LIKE :searchParam OR
            l.manage LIKE :searchParam OR
            CONCAT(lib.firstname, ' ', lib.lastname) LIKE :searchParam)
        ORDER BY $sortPatronColumn $sortPatronOrder
        LIMIT :offset, :itemsPatronPerPage";

$stmt = $pdo->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPatronPerPage', $itemsPatronPerPage, PDO::PARAM_INT);
$stmt->execute();

$logsPatronList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuery = "SELECT COUNT(*) 
               FROM logs l
               JOIN librarians lib ON l.librarians_id = lib.librarians_id
               WHERE 
                   l.page = 'Books' AND
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
$totalPatron = $totalStmt->fetchColumn();

echo json_encode([
    'patronList' => $logsPatronList, // Change 'logsPatronList' to 'patronList'
    'totalPatronLogs' => $totalPatron // Change 'totalPatron' to 'totalPatronLogs'
]);

?>
