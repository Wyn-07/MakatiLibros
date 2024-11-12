<?php
function getDelinquentInfo($pdo)
{
    $query = "
        SELECT 
            d.delinquent_id,
            d.borrow_id,
            d.status AS delinquent_status,
            b.borrow_date,
            b.return_date,
            p.patrons_id,
            p.firstname AS patron_firstname,
            p.middlename AS patron_middlename,
            p.lastname AS patron_lastname,
            p.suffix AS patron_suffix,
            p.address AS patron_address,
            p.contact AS patron_contact
        FROM 
            delinquent d
        LEFT JOIN 
            borrow b ON d.borrow_id = b.borrow_id
        LEFT JOIN 
            patrons p ON b.patrons_id = p.patrons_id
        ORDER BY 
            d.delinquent_id
    ";

    $stmt = $pdo->prepare($query);
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        return [];
    }

    $delinquentInfo = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $delinquentInfo[] = [
            'delinquent_id' => $row['delinquent_id'],
            'borrow_id' => $row['borrow_id'],
            'delinquent_status' => $row['delinquent_status'],
            'borrow_date' => date("F d, Y", strtotime($row['borrow_date'])),
            'return_date' => date("F d, Y", strtotime($row['return_date'])),
            'patrons_id' => $row['patrons_id'],
            'patron_firstname' => $row['patron_firstname'],
            'patron_middlename' => $row['patron_middlename'],
            'patron_lastname' => $row['patron_lastname'],
            'patron_suffix' => $row['patron_suffix'],
            'patron_address' => $row['patron_address'],
            'patron_contact' => $row['patron_contact']
        ];
    }
    return $delinquentInfo;
}
?>
