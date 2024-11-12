<?php

// Check if the user is logged in and has an email stored in the session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

try {
    // Prepare and execute the query to fetch data from the librarians table
    $query = "SELECT * FROM librarians WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the librarian data
    $librarian = $stmt->fetch(PDO::FETCH_ASSOC);

    // If not found in the librarians table, check the admin table
    if (!$librarian) {
        // Check the admin table for the user
        $query = "SELECT * FROM admin WHERE email = :email";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the admin data
        $librarian = $admin; // Set librarian variable to admin data
    }

    // Check if the user is an admin based on admin table presence
    $isAdmin = isset($librarian['admin_id']); // Admin check based on admin_id field in the admin table

    if ($librarian) {
        // Data fetched successfully, you can now display it
        $librarian_id = isset($librarian['librarians_id']) ? $librarian['librarians_id'] : (isset($librarian['admin_id']) ? $librarian['admin_id'] : ''); // Use appropriate ID field
        $firstname = $librarian['firstname'] ?? ''; // Null coalescing for safety
        $middlename = $librarian['middlename'] ?? ''; 
        $lastname = $librarian['lastname'] ?? ''; 
        $suffix = $librarian['suffix'] ?? ''; // Admin may not have a suffix
        $birthdate = $librarian['birthdate'] ?? ''; // Admin may not have a birthdate
        $age = $librarian['age'] ?? ''; // Admin may not have an age
        $gender = $librarian['gender'] ?? ''; // Admin may not have a gender
        $contact = $librarian['contact'] ?? ''; // Default to empty if not set
        $address = $librarian['address'] ?? ''; // Default to empty if not set
        
        // Check if the image field exists for the librarian or admin
        $image = $librarian['image'] ?? 'default-image.jfif'; // Default image if not available

   
    } else {
        // Handle the case where no data is found
        $_SESSION['error_message'] = "Session Expired";
        $_SESSION['error_display'] = "flex";
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    header("Location: logout.php");
    exit();
}
?>
