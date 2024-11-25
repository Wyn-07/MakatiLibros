<?php
require '../../connection.php';

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'date_time';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['audit_id', 'date_time', 'page', 'description', 'user_name'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'date_time';
}

$offset = ($page - 1) * $itemsPerPage;

$sql = "
    SELECT * FROM (
        SELECT
            'librarian' AS user_type,
            la.librarian_audit_id AS audit_id,
            la.date_time,
            la.old_data,
            la.new_data,
            la.librarians_id AS user_id,
            CONCAT(lib.firstname, ' ', lib.lastname) AS user_name,
            la.page,
            la.description
        FROM 
            librarian_audit la
        JOIN 
            librarians lib ON la.librarians_id = lib.librarians_id

        UNION

        SELECT
            'admin' AS user_type,
            aa.admin_audit_id AS audit_id,
            aa.date_time,
            aa.old_data,
            aa.new_data,
            aa.admin_id AS user_id,
            ad.name AS user_name,
            aa.page,
            aa.description
        FROM 
            admin_audit aa
        JOIN 
            admin ad ON aa.admin_id = ad.admin_id
    ) combined
    WHERE
        audit_id LIKE :searchParam OR
        date_time LIKE :searchParam OR
        old_data LIKE :searchParam OR
        new_data LIKE :searchParam OR
        page LIKE :searchParam OR
        description LIKE :searchParam OR
        user_name LIKE :searchParam
    ORDER BY $sortColumn $sortOrder
    LIMIT :offset, :itemsPerPage";

$stmt = $pdo->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();

$logsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuery = "
    SELECT COUNT(*) FROM (
        SELECT
            la.librarian_audit_id AS audit_id,
            la.date_time,
            la.old_data,
            la.new_data,
            la.page,
            la.description,
            CONCAT(lib.firstname, ' ', lib.lastname) AS user_name
        FROM 
            librarian_audit la
        JOIN 
            librarians lib ON la.librarians_id = lib.librarians_id

        UNION ALL

        SELECT
            aa.admin_audit_id AS audit_id,
            aa.date_time,
            aa.old_data,
            aa.new_data,
            aa.page,
            aa.description,
            ad.name AS user_name
        FROM 
            admin_audit aa
        JOIN 
            admin ad ON aa.admin_id = ad.admin_id
    ) combined
    WHERE
        audit_id LIKE :searchParam OR
        date_time LIKE :searchParam OR
        old_data LIKE :searchParam OR
        new_data LIKE :searchParam OR
        page LIKE :searchParam OR
        description LIKE :searchParam OR
        user_name LIKE :searchParam";


$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$totalStmt->execute();
$totalLogs = $totalStmt->fetchColumn();

echo json_encode([
    'logsList' => $logsList,
    'totalLogs' => $totalLogs
]);
?>
