<?php
require '../../connection.php';

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsBooksPerPage = isset($_GET['itemsBooksPerPage']) ? (int)$_GET['itemsBooksPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortBooksColumn = isset($_GET['sortBooksColumn']) ? $_GET['sortBooksColumn'] : 'logs_id';
$sortBooksOrder = isset($_GET['sortBooksOrder']) && $_GET['sortBooksOrder'] == 'desc' ? 'DESC' : 'ASC';

// Ensure valid column name
$validColumns = ['logs_id', 'date_time', 'page', 'manage', 'librarians_id'];
if (!in_array($sortBooksColumn, $validColumns)) {
    $sortBooksColumn = 'logs_id';
}

$offset = ($page - 1) * $itemsBooksPerPage;

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
        ORDER BY $sortBooksColumn $sortBooksOrder
        LIMIT :offset, :itemsBooksPerPage";

$stmt = $pdo->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsBooksPerPage', $itemsBooksPerPage, PDO::PARAM_INT);
$stmt->execute();

$logsBooksList = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
$totalBooks = $totalStmt->fetchColumn();

echo json_encode([
    'bookList' => $logsBooksList, // Change 'logsBooksList' to 'bookList'
    'totalBooksLogs' => $totalBooks // Change 'totalBooks' to 'totalBooksLogs'
]);

?>
