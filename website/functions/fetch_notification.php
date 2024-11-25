<?php


// Assuming the logged-in patron's ID is stored in the session
$patrons_id = $_SESSION['patrons_id'];

if (!$patrons_id) {
    die("Patron not logged in.");
}

try {
    // Fetch notifications and related data for the logged-in patron, excluding 'Pending' borrow status
    $query = "
        SELECT 
            n.notif_id,
            n.seen,
            b.borrow_id,
            b.book_id,
            b.status AS borrow_status,
            b.accepted_date,
            b.accepted_time,
            b.borrow_date,
            b.borrow_time,
            b.return_date,
            b.return_time,
            bk.title AS book_title,
            bk.image AS book_image
        FROM 
            notification n
        JOIN 
            borrow b ON n.borrow_id = b.borrow_id
        JOIN 
            books bk ON b.book_id = bk.book_id
        WHERE 
            b.patrons_id = :patrons_id
            AND b.status != 'Pending'  -- Exclude 'Pending' status
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Today's date for comparisons
    $today = new DateTime();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
