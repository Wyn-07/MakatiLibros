<?php

// Check if the user is logged in and has an email stored in the session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

try {
    // Prepare and execute the query to fetch data based on the email
    $query = "SELECT * FROM librarians WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the patron data
    $librarian = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($librarian) {
        // Data fetched successfully, you can now display it
        $librarian_id = $librarian['librarians_id'];
        $firstname = $librarian['firstname'];
        $middlename = $librarian['middlename'];
        $lastname = $librarian['lastname'];
        $suffix = $librarian['suffix'];
        $birthdate = $librarian['birthdate'];
        $age = $librarian['age'];
        $gender = $librarian['gender'];
        $contact = $librarian['contact'];
        $address = $librarian['address'];

        $date = DateTime::createFromFormat('m/d/Y', $birthdate);
        $formattedBirthdate = $date->format('Y-m-d');
    } else {
        // Handle the case where no data is found
        echo "No librarian data found for this email.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

?>