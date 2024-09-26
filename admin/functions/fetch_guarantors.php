<?php
function getGuarantors($pdo)
{
    $query = "SELECT 
                guarantor_id, 
                firstname, 
                middlename, 
                lastname, 
                suffix, 
                contact, 
                address, 
                company_name, 
                company_contact, 
                company_address 
              FROM 
                guarantor
              ORDER BY 
                lastname, firstname";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $guarantors = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $guarantors[] = [
            'guarantor_id' => $row['guarantor_id'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'lastname' => $row['lastname'],
            'suffix' => $row['suffix'],
            'contact' => $row['contact'],
            'address' => $row['address'],
            'company_name' => $row['company_name'],
            'company_contact' => $row['company_contact'],
            'company_address' => $row['company_address']
        ];
    }
    return $guarantors;
}

function getGuarantorsNames($pdo)
{
    $query = "SELECT 
                guarantor_id, 
                firstname, 
                middlename, 
                lastname, 
                suffix 
              FROM 
                guarantor
              ORDER BY 
                lastname, firstname";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $guarantorsName = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $guarantorsName[] = [
            'guarantor_id' => $row['guarantor_id'],
            'name' => $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']
        ];
    }

    return $guarantorsName;
}
