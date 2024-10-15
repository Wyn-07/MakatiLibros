<?php

// Check if the user is logged in and has an email stored in the session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

try {
    // Prepare and execute the query to fetch data based on the email
    $query = "SELECT * FROM patrons WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the patron data
    $patron = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patron) {
        // Data fetched successfully, you can now display it
        $patron_id = $patron['patrons_id'];
        $firstname = $patron['firstname'];
        $middlename = $patron['middlename'];
        $lastname = $patron['lastname'];
        $suffix = $patron['suffix'];
        $birthdate = $patron['birthdate'];
        $age = $patron['age'];
        $gender = $patron['gender'];
        $contact = $patron['contact'];
        $address = $patron['address'];
        $company_name = $patron['company_name'];
        $company_contact = $patron['company_contact'];
        $company_address = $patron['company_address'];
        $image = $patron['image'];

    } else {
        // Handle the case where no data is found
        echo "No librarian data found for this email.";
        exit();
    }
} catch (PDOException $e) {
    header("Location: logout.php");
    exit();
}
