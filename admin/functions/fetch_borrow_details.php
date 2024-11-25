<?php
include '../../connection.php';

// Get parameters
$month = $_GET['month'];
$type = $_GET['type'];
$year = intval($_GET['year']); // Sanitize input

// Convert month name to month number
$monthNumber = date('n', strtotime($month));

// Base query to fetch data based on the month, type, and year, sorted by date (ascending)
$query = "
    SELECT 
        STR_TO_DATE(borrow.borrow_date, '%m/%d/%Y') AS date, 
        books.title AS bookTitle, 
        CONCAT(patrons.firstname, ' ', patrons.lastname) AS patron
    FROM borrow
    INNER JOIN books ON borrow.book_id = books.book_id
    INNER JOIN patrons ON borrow.patrons_id = patrons.patrons_id
    WHERE YEAR(STR_TO_DATE(borrow.borrow_date, '%m/%d/%Y')) = :year
    AND MONTH(STR_TO_DATE(borrow.borrow_date, '%m/%d/%Y')) = :month
";

// Add conditional filters based on the type
if ($type === 'borrowed') {
    $query .= " AND borrow.borrow_date != 'Pending'"; // Exclude borrow_date if 'borrowed'
} elseif ($type === 'returned') {
    $query .= " AND borrow.return_date != 'Pending'"; // Exclude return_date if 'returned'
}

$query .= " ORDER BY STR_TO_DATE(borrow.borrow_date, '%m/%d/%Y') ASC";

// Prepare and execute query
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':year' => $year,
    ':month' => $monthNumber
]);

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data['details'][] = $row;
}

// Return results as JSON
echo json_encode($data);
?>
