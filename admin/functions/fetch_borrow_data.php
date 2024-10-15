<?php
include '../../connection.php';

if (isset($_GET['year'])) {
    $year = intval($_GET['year']);

    // SQL query to get monthly borrow and return transactions for the selected year
    $sql = "SELECT 
                MONTH(STR_TO_DATE(borrow_date, '%m/%d/%Y')) AS month, 
                SUM(CASE WHEN borrow_date IS NOT NULL THEN 1 ELSE 0 END) AS borrowed,
                SUM(CASE WHEN return_date IS NOT NULL THEN 1 ELSE 0 END) AS returned
            FROM borrow 
            WHERE YEAR(STR_TO_DATE(borrow_date, '%m/%d/%Y')) = :year 
            GROUP BY MONTH(STR_TO_DATE(borrow_date, '%m/%d/%Y'))";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['year' => $year]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare labels (months) and data (transactions count)
    $labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $borrowedValues = array_fill(0, 12, 0); // Initialize an array for borrowed counts
    $returnedValues = array_fill(0, 12, 0); // Initialize an array for returned counts

    foreach ($results as $row) {
        $monthIndex = $row['month'] - 1;  // Array index for the month
        $borrowedValues[$monthIndex] = $row['borrowed'];
        $returnedValues[$monthIndex] = $row['returned'];
    }

    // Return the data in JSON format
    echo json_encode([
        'labels' => $labels,
        'borrowed' => $borrowedValues,
        'returned' => $returnedValues
    ]);
} else {
    echo json_encode(['error' => 'No year selected']);
}
?>
