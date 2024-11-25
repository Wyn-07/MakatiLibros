<?php
include '../../connection.php';

if (isset($_GET['month'])) {
    $month = $_GET['month']; // Format: YYYY-MM

    // SQL query to get daily borrow and return transactions for the selected month
    $sql = "SELECT 
                DAY(STR_TO_DATE(borrow_date, '%m/%d/%Y')) AS day, 
                SUM(CASE WHEN borrow_date != 'Pending' THEN 1 ELSE 0 END) AS borrowed,
                SUM(CASE WHEN return_date != 'Pending' THEN 1 ELSE 0 END) AS returned
            FROM borrow 
            WHERE DATE_FORMAT(STR_TO_DATE(borrow_date, '%m/%d/%Y'), '%Y-%m') = :month 
            GROUP BY DAY(STR_TO_DATE(borrow_date, '%m/%d/%Y'))";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['month' => $month]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare labels (days) and data (transactions count)
    $labels = range(1, 31); // Days of the month
    $borrowedValues = array_fill(0, 31, 0); // Initialize an array for borrowed counts
    $returnedValues = array_fill(0, 31, 0); // Initialize an array for returned counts

    foreach ($results as $row) {
        $dayIndex = $row['day'] - 1;  // Array index for the day
        $borrowedValues[$dayIndex] = $row['borrowed'];
        $returnedValues[$dayIndex] = $row['returned'];
    }

    // Return the data in JSON format
    echo json_encode([
        'labels' => $labels,
        'borrowed' => $borrowedValues,
        'returned' => $returnedValues
    ]);
} else {
    echo json_encode(['error' => 'No month selected']);
}
?>
