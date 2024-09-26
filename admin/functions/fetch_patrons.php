<?php
function getPatrons($pdo)
{
    $query = "SELECT patrons_id, firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, interest, email, password 
              FROM patrons
              ORDER BY lastname, firstname";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $patrons = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $patrons[] = [
            'patrons_id' => $row['patrons_id'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'lastname' => $row['lastname'],
            'suffix' => $row['suffix'],
            'birthdate' => $row['birthdate'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'contact' => $row['contact'],
            'address' => $row['address'],
            'interest' => $row['interest'],
            'email' => $row['email'],
            'password' => $row['password']
        ];
    }
    return $patrons;
}


function getPatronsNames($pdo)
{
    $query = "SELECT patrons_id, firstname, middlename, lastname, suffix 
              FROM patrons
              ORDER BY lastname, firstname";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $patronsName = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $patronsName[] = [
            'patrons_id' => $row['patrons_id'],
            'name' => $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']
        ];
    }

    return $patronsName;
}
