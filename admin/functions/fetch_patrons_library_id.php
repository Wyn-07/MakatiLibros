<?php
function getPatronsIDInfo($pdo)
{
    $query = "
        SELECT 
            pl.library_id, 
            pl.patrons_id, 
            pl.guarantor_id, 
            pl.date_issued, 
            pl.valid_until,
            p.firstname AS patron_firstname,
            p.middlename AS patron_middlename,
            p.lastname AS patron_lastname,
            p.suffix AS patron_suffix,
            p.address AS patron_address,          -- Added address
            p.company_name AS patron_company_name, -- Added company_name
            p.image AS patron_image,               -- Fetch patron image
            g.firstname AS guarantor_firstname,
            g.middlename AS guarantor_middlename,
            g.lastname AS guarantor_lastname,
            g.suffix AS guarantor_suffix
        FROM 
            patrons_library_id pl
        LEFT JOIN 
            patrons p ON pl.patrons_id = p.patrons_id
        LEFT JOIN 
            guarantor g ON pl.guarantor_id = g.guarantor_id
        ORDER BY 
            pl.library_id
    ";

    $stmt = $pdo->prepare($query);
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        return [];
    }

    $patronInfo = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $formattedIssuedUntil = date("F d, Y", strtotime($row['date_issued']));

        $formattedValidUntil = date("F d, Y", strtotime($row['valid_until']));

        $patronInfo[] = [
            'library_id' => $row['library_id'],
            'patrons_id' => $row['patrons_id'],
            'guarantor_id' => $row['guarantor_id'],
            'date_issued' => $formattedIssuedUntil,
            'valid_until' => $formattedValidUntil,
            'patron_firstname' => $row['patron_firstname'],
            'patron_middlename' => $row['patron_middlename'],
            'patron_lastname' => $row['patron_lastname'],
            'patron_suffix' => $row['patron_suffix'],
            'patron_address' => $row['patron_address'],             // Added address
            'patron_company_name' => $row['patron_company_name'],
            'patron_image' => $row['patron_image'],     
            'guarantor_firstname' => $row['guarantor_firstname'],
            'guarantor_middlename' => $row['guarantor_middlename'],
            'guarantor_lastname' => $row['guarantor_lastname'],
            'guarantor_suffix' => $row['guarantor_suffix']
        ];
    }
    return $patronInfo;
}
