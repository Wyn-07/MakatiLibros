<?php

// Check if the user is logged in and has an email stored in the session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

try {
    // Prepare and execute the query to fetch only the patrons_id and the guarantor data based on the email
    $query = "
        SELECT p.patrons_id, 
               g.guarantor_id, g.firstname AS guarantor_firstname, g.middlename AS guarantor_middlename, 
               g.lastname AS guarantor_lastname, g.suffix AS guarantor_suffix, g.contact AS guarantor_contact, 
               g.address AS guarantor_address, g.company_name AS guarantor_company_name, 
               g.company_contact AS guarantor_company_contact, g.company_address AS guarantor_company_address,
               g.sign
        FROM patrons p
        LEFT JOIN guarantor g ON p.patrons_id = g.patrons_id
        WHERE p.email = :email
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the guarantor data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Patron ID
        $patron_id = $data['patrons_id'];

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
        $guarantor_sign = $data['sign'];


    } else {
        // Handle the case where no guarantor data is found
        echo "No guarantor data found for this patron.";
        exit();
    }
} catch (PDOException $e) {
    // Log the error or handle as needed
    header("Location: logout.php");
    exit();
}
?>
