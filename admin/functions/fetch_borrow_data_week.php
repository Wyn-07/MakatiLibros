<?php
include '../../connection.php';

if (isset($_GET['month'])) {
    $month = $_GET['month']; // Format: YYYY-MM

    // SQL query to get weekly borrow and return transactions for the selected month
    $sql = "SELECT 
                WEEK(STR_TO_DATE(borrow_date, '%m/%d/%Y')) AS week, 
                SUM(CASE WHEN borrow_date != 'Pending' THEN 1 ELSE 0 END) AS borrowed,
                SUM(CASE WHEN return_date != 'Pending' THEN 1 ELSE 0 END) AS returned
            FROM borrow 
            WHERE DATE_FORMAT(STR_TO_DATE(borrow_date, '%m/%d/%Y'), '%Y-%m') = :month 
            GROUP BY WEEK(STR_TO_DATE(borrow_date, '%m/%d/%Y'))
            ORDER BY week ASC";  // Ensure the weeks are ordered

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['month' => $month]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare labels (weeks) and data (transactions count)
    $weekLabels = [];
    $borrowedValues = [];
    $returnedValues = [];

    // Limit to the first 4-5 weeks
    $maxWeeks = 5;
    $weekCount = 0;

    // Loop through the results and populate the arrays
    foreach ($results as $row) {
        if ($weekCount >= $maxWeeks) break; // Stop after 4-5 weeks

        $weekLabels[] = "Week " . $row['week'];
        $borrowedValues[] = $row['borrowed'];
        $returnedValues[] = $row['returned'];
        $weekCount++;
    }

    // Return the data in JSON format
    echo json_encode([
        'labels' => $weekLabels,
        'borrowed' => $borrowedValues,
        'returned' => $returnedValues
    ]);
} else {
    echo json_encode(['error' => 'No month selected']);
}
?>
