<?php
function getPatronLogs($pdo)
{
    $query = "SELECT log_id, log_date, firstname, middlename, lastname, suffix, age, gender, barangay, city, purpose, sector, sector_details 
              FROM patron_logs";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $patronLogs = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $patronLogs[] = [
            'log_id' => $row['log_id'],
            'log_date' => $row['log_date'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'lastname' => $row['lastname'],
            'suffix' => $row['suffix'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'barangay' => $row['barangay'],
            'city' => $row['city'],
            'purpose' => $row['purpose'],
            'sector' => $row['sector'],
            'sector_details' => $row['sector_details']
        ];
    }
    return $patronLogs;
}
?>
