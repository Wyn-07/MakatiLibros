<?php
require '../../connection.php'; 

// Fetch parameters from GET request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sortPatronColumn = isset($_GET['sortPatronColumn']) ? $_GET['sortPatronColumn'] : 'patrons_name';
$sortPatronOrder = isset($_GET['sortPatronOrder']) && $_GET['sortPatronOrder'] == 'desc' ? 'DESC' : 'ASC';

// Define valid columns for sorting
$validColumns = ['patrons_name', 'card_id', 'guarantor', 'patron_status'];

// Ensure valid column name for sorting
if (!in_array($sortPatronColumn, $validColumns)) {
    $sortPatronColumn = 'patrons_name'; // Default column
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Start SQL query with joins to include card_id, guarantor details, and delinquent status
$query = "
    SELECT 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) AS patrons_name,
        pl.card_id,
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) AS guarantor,
        p.patrons_id,
        -- Determine patron status
        CASE 
            WHEN dl.latest_status = 'Unresolved' THEN 'Delinquent'
            ELSE 'Responsible'
        END AS patron_status,
        p.firstname AS patron_firstname,
        p.middlename AS patron_middlename, 
        p.lastname AS patron_lastname,
        p.suffix AS patron_suffix, 
        p.birthdate AS patron_birthdate, 
        p.age AS patron_age, 
        p.gender AS patron_gender,
        p.contact AS patron_contact,
        p.house_num AS patron_house_num,
        p.building AS patron_building,
        p.streets AS patron_street,
        p.barangay AS patron_barangay,
        p.company_name AS patron_company_name, 
        p.company_contact AS patron_company_contact,
        p.company_address AS patron_company_address,
        p.email AS patron_email,
        p.image AS patron_image,
        p.sign AS patron_sign,
        p.valid_id,
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
        g.company_address AS guarantor_company_address,
        g.sign AS guarantor_sign
    FROM 
        patrons p
    LEFT JOIN 
        patrons_library_id pl ON p.patrons_id = pl.patrons_id
    LEFT JOIN 
        guarantor g ON pl.guarantor_id = g.guarantor_id
    LEFT JOIN (
        SELECT 
            b.patrons_id,
            MAX(d.delinquent_id) AS latest_delinquent_id,
            (SELECT d2.status 
             FROM delinquent d2 
             WHERE d2.delinquent_id = MAX(d.delinquent_id)) AS latest_status
        FROM 
            delinquent d
        INNER JOIN 
            borrow b ON d.borrow_id = b.borrow_id
        GROUP BY 
            b.patrons_id
    ) dl ON dl.patrons_id = p.patrons_id
    WHERE 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) LIKE ? OR
        pl.card_id LIKE ? OR
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) LIKE ? OR
        CASE 
            WHEN dl.latest_status = 'Unresolved' THEN 'Delinquent'
            ELSE 'Responsible'
        END LIKE ?
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
$stmt->bindParam(4, $searchParam, PDO::PARAM_STR);

// Bind pagination parameters
$stmt->bindParam(5, $offset, PDO::PARAM_INT);
$stmt->bindParam(6, $itemsPerPage, PDO::PARAM_INT);

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
    LEFT JOIN (
        SELECT 
            b.patrons_id,
            MAX(d.delinquent_id) AS latest_delinquent_id,
            (SELECT d2.status 
             FROM delinquent d2 
             WHERE d2.delinquent_id = MAX(d.delinquent_id)) AS latest_status
        FROM 
            delinquent d
        INNER JOIN 
            borrow b ON d.borrow_id = b.borrow_id
        GROUP BY 
            b.patrons_id
    ) dl ON dl.patrons_id = p.patrons_id
    WHERE 
        CONCAT(p.firstname, ' ', IFNULL(p.middlename, ''), ' ', p.lastname, ' ', IFNULL(p.suffix, '')) LIKE ? OR
        pl.card_id LIKE ? OR
        CONCAT(g.firstname, ' ', IFNULL(g.middlename, ''), ' ', g.lastname, ' ', IFNULL(g.suffix, '')) LIKE ? OR
        CASE 
            WHEN dl.latest_status = 'Unresolved' THEN 'Delinquent'
            ELSE 'Responsible'
        END LIKE ?
";

// Prepare and execute the total count query
$totalStmt = $pdo->prepare($totalQuery);

// Bind search parameters for total count
$totalStmt->bindParam(1, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(2, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(3, $searchParam, PDO::PARAM_STR);
$totalStmt->bindParam(4, $searchParam, PDO::PARAM_STR);

$totalStmt->execute();
$totalPatrons = $totalStmt->fetchColumn();

// Output as JSON for JavaScript to process
echo json_encode([
    'patronList' => $patronList,
    'totalPatrons' => $totalPatrons
]);
?>
