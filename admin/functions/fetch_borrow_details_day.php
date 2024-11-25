<?php
include '../../connection.php';

// Get parameters
$day = $_GET['day'];   // 'day' from the query
$type = $_GET['type']; // 'type' (borrowed or returned)
$month = $_GET['month']; // 'month' in format YYYY-MM

// Extract the year and month from the 'month' parameter
$year = substr($month, 0, 4);
$monthNumber = substr($month, 5, 2);

// Prepare the query
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
    AND DAY(STR_TO_DATE(borrow.borrow_date, '%m/%d/%Y')) = :day
";

// Add conditional filters based on the 'type'
if ($type === 'borrowed') {
    $query .= " AND borrow.borrow_date != 'Pending'"; // Exclude if borrow_date is 'Pending'
} elseif ($type === 'returned') {
    $query .= " AND borrow.return_date != 'Pending'"; // Exclude if return_date is 'Pending'
}

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':year' => $year,
    ':month' => $monthNumber,
    ':day' => $day
]);

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data['details'][] = $row;
}

// Ensure 'details' is always an array, even if empty
if (empty($data['details'])) {
    $data['details'] = []; // Return an empty array if no results
}

// Return results as JSON
echo json_encode($data);
?>
