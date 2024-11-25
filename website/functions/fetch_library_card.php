<?php

$email = $_SESSION['email'];


// Query to fetch patron, guarantor, and library card details based on the logged-in user's email
$query = "
        SELECT 
            p.patrons_id, 
            p.firstname AS patron_firstname, p.middlename AS patron_middlename, p.lastname AS patron_lastname, p.suffix AS patron_suffix,
            p.email AS patron_email, p.contact AS patron_contact, 
            p.house_num, p.streets, p.barangay, 
            p.company_name AS patron_company_name, p.company_contact AS patron_company_contact, 
            p.company_address AS patron_company_address,
            l.card_id, l.date_issued, l.valid_until,
            g.guarantor_id, 
            g.firstname AS guarantor_firstname, g.middlename AS guarantor_middlename, g.lastname AS guarantor_lastname, 
            g.suffix AS guarantor_suffix, g.contact AS guarantor_contact, g.address AS guarantor_address, 
            g.company_name AS guarantor_company_name, g.company_contact AS guarantor_company_contact, 
            g.company_address AS guarantor_company_address
        FROM patrons p
        LEFT JOIN patrons_library_id l ON p.patrons_id = l.patrons_id
        LEFT JOIN guarantor g ON l.guarantor_id = g.guarantor_id
        WHERE p.email = :email
    ";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Fetch the data
$data = $stmt->fetch();

if ($data) {
    // Patron data
    $patron_id = $data['patrons_id'];
    $patron_firstname = $data['patron_firstname'];
    $patron_middlename = $data['patron_middlename'];
    $patron_lastname = $data['patron_lastname'];
    $patron_suffix = $data['patron_suffix'];
    $patron_email = $data['patron_email'];
    $patron_contact = $data['patron_contact'];
    $patron_house_num = $data['house_num'];
    $patron_street = $data['streets'];
    $patron_barangay = $data['barangay'];
    $patron_company_name = $data['patron_company_name'];
    $patron_company_contact = $data['patron_company_contact'];
    $patron_company_address = $data['patron_company_address'];

    // Library card data
    $card_id = $data['card_id'];
    $date_issued = $data['date_issued'];
    $valid_until = $data['valid_until'];

    // Guarantor data
    $guarantor_id = $data['guarantor_id'];
    $guarantor_firstname = $data['guarantor_firstname'];
    $guarantor_middlename = $data['guarantor_middlename'];
    $guarantor_lastname = $data['guarantor_lastname'];
    $guarantor_suffix = $data['guarantor_suffix'];
    $guarantor_contact = $data['guarantor_contact'];
    $guarantor_address = $data['guarantor_address'];
    $guarantor_company_name = $data['guarantor_company_name'];
    $guarantor_company_contact = $data['guarantor_company_contact'];
    $guarantor_company_address = $data['guarantor_company_address'];

    // Output or use the data as needed
} else {
    // Handle the case where no data is found
    echo "No data found for this patron.";
}
