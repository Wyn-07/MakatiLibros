<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "librodb";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if patron ID is sent
if (isset($_GET['patron_id'])) {
    $patronId = $conn->real_escape_string($_GET['patron_id']);

    // Query to get book title and image for the patron
    $query = "
        SELECT books.title, books.image 
        FROM books 
        INNER JOIN borrow ON books.book_id = borrow.book_id
        WHERE borrow.patrons_id = '1'
          AND borrow.status = 'Borrowed'
    ";

    $result = $conn->query($query);

    $books = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    // Return the books as JSON
    echo json_encode($books);
} else {
    echo json_encode([]); // Return empty array if no patron ID is provided
}

$conn->close();
?>
