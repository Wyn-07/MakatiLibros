<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortPatronColumn = isset($_GET['sortPatronColumn']) ? $_GET['sortPatronColumn'] : 'patrons_name';
$sortPatronOrder = isset($_GET['sortPatronOrder']) && $_GET['sortPatronOrder'] == 'desc' ? 'DESC' : 'ASC';

// Define valid columns for sorting
$validColumns = ['patrons_name', 'card_id', 'guarantor'];

// Ensure valid column name for sorting
if (!in_array($sortPatronColumn, $validColumns)) {
    $sortPatronColumn = 'patrons_name'; // Default column
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query with joins to include card_id and guarantor details
$query = "
    SELECT 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) AS patrons_name,
        pl.card_id,
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) AS guarantor,
        p.patrons_id,
        p.firstname AS patron_firstname,
        p.middlename AS patron_middlename, 
        p.lastname AS patron_lastname,
        p.suffix AS patron_suffix, 
        p.birthdate AS patron_birthdate, 
        p.age AS patron_age, 
        p.gender AS patron_gender,
        p.contact AS patron_contact,
        p.address AS patron_address,
        p.company_name AS patron_company_name, 
        p.company_contact AS patron_company_contact,
        p.company_address AS patron_company_address,
        p.email AS patron_email,
        p.image AS patron_image,
        pl.date_issued, 
        pl.valid_until,
        g.guarantor_id, 
        g.firstname AS guarantor_firstname, 
        g.middlename AS guarantor_middlename, 
        g.lastname AS guarantor_lastname, 
        g.suffix AS guarantor_suffix,
        g.contact AS guarantor_contact, 
        g.address AS guarantor_address, 
        g.company_name AS guarantor_company_name, 
        g.company_contact AS guarantor_company_contact,
        g.company_address AS guarantor_company_address
    FROM 
        patrons p
    LEFT JOIN 
        patrons_library_id pl ON p.patrons_id = pl.patrons_id
    LEFT JOIN 
        guarantor g ON pl.guarantor_id = g.guarantor_id
    WHERE 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) LIKE ? OR
        pl.card_id LIKE ? OR
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) LIKE ? 
";

// Modify sorting for `card_id` to sort by numeric part after last dash if necessary
if ($sortPatronColumn === 'card_id') {
    // Sorting by the numeric part after the last hyphen
    $query .= " ORDER BY CAST(SUBSTRING_INDEX(pl.card_id, '-', -1) AS UNSIGNED) $sortPatronOrder";
} else {
    // Default sorting by other columns
    $query .= " ORDER BY $sortPatronColumn $sortPatronOrder";
}

// Add pagination
$query .= " LIMIT ?, ?";

// Prepare and execute SQL query
$stmt = $pdo->prepare($query);

// Bind search parameters for specified columns
$searchParam = "%$search%";
$stmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$stmt->bindParam(3, $searchParam, PDO::PARAM_STR);

// Bind pagination parameters
$stmt->bindParam(4, $offset, PDO::PARAM_INT);
$stmt->bindParam(5, $itemsPerPage, PDO::PARAM_INT);

$stmt->execute();

// Fetch patron records
$patronList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total patron records for pagination
$totalQuery = "
    SELECT COUNT(*) 
    FROM 
        patrons p
    LEFT JOIN 
        patrons_library_id pl ON p.patrons_id = pl.patrons_id
    LEFT JOIN 
        guarantor g ON pl.guarantor_id = g.guarantor_id
    WHERE 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) LIKE ? OR
        pl.card_id LIKE ? OR
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) LIKE ? 
";

// Prepare and execute the total count query
$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalPatrons = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'patronList' => $patronList,
    'totalPatrons' => $totalPatrons
]);
?>
