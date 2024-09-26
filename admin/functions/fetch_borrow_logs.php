<?php
function getBorrowLogs($pdo)
{
    $query = "SELECT log_id, log_date, category, book_title, name, age, gender, barangay, city 
              FROM borrow_logs";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $borrowLogs = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $borrowLogs[] = [
            'log_id' => $row['log_id'],
            'log_date' => $row['log_date'],
            'category' => $row['category'],
            'book_title' => $row['book_title'],
            'name' => $row['name'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'barangay' => $row['barangay'],
            'city' => $row['city']
        ];
    }
    return $borrowLogs;
}
?>
