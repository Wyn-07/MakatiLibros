<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortLibrarianColumn = isset($_GET['sortLibrarianColumn']) ? $_GET['sortLibrarianColumn'] : 'firstname';
$sortLibrarianOrder = isset($_GET['sortLibrarianOrder']) && $_GET['sortLibrarianOrder'] == 'desc' ? 'DESC' : 'ASC';

// Define valid columns for sorting
// Define valid columns for sorting and searching
$validColumns = ['librarian_name', 'contact', 'email'];

// Ensure valid column name for sorting
if (!in_array($sortLibrarianColumn, $validColumns)) {
    $sortLibrarianColumn = 'firstname'; // Default column
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query with librarian details
$query = "
    SELECT 
        CONCAT(l.firstname, ' ', IFNULL(l.middlename, ''), ' ', l.lastname, ' ', IFNULL(l.suffix, '')) AS librarian_name,
        l.librarians_id,
        l.firstname AS librarian_firstname,
        l.middlename AS librarian_middlename, 
        l.lastname AS librarian_lastname,
        l.suffix AS librarian_suffix, 
        l.birthdate AS librarian_birthdate, 
        l.age AS librarian_age, 
        l.gender AS librarian_gender,
        l.contact AS librarian_contact,
        l.address AS librarian_address,
        l.email AS librarian_email,
        l.image AS librarian_image
    FROM 
        librarians l
    WHERE 
        (CONCAT(l.firstname, ' ', IFNULL(l.middlename, ''), ' ', l.lastname, ' ', IFNULL(l.suffix, '')) LIKE ? OR
        l.email LIKE ? OR 
        l.contact LIKE ?)";

// Modify sorting for librarian columns
if ($sortLibrarianColumn === 'email') {
    $query .= " ORDER BY l.email $sortLibrarianOrder";
} else {
    $query .= " ORDER BY $sortLibrarianColumn $sortLibrarianOrder";
}

// Add pagination
$query .= " LIMIT ?, ?";

// Prepare and execute SQL query
$stmt = $pdo->prepare($query);

// Bind search parameters for specified columns
$searchParam = "%$search%";
$stmt->bindParam(1, $searchParam, PDO::PARAM_STR); // For librarian_name
$stmt->bindParam(2, $searchParam, PDO::PARAM_STR); // For email
$stmt->bindParam(3, $searchParam, PDO::PARAM_STR); // For contact

// Bind pagination parameters
$stmt->bindParam(4, $offset, PDO::PARAM_INT);
$stmt->bindParam(5, $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch librarian records
$librarianList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total librarian records for pagination
$totalQuery = "
    SELECT COUNT(*) 
    FROM 
        librarians l
    WHERE 
        (CONCAT(l.firstname, ' ', IFNULL(l.middlename, ''), ' ', l.lastname, ' ', IFNULL(l.suffix, '')) LIKE ? OR
        l.email LIKE ? OR 
        l.contact LIKE ?)
";

// Prepare and execute the total count query
$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR); // For librarian_name
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR); // For email
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR); // For contact

$totalStmt->execute();
$totalLibrarians = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'librarianList' => $librarianList,
    'totalLibrarians' => $totalLibrarians
]);

?>
