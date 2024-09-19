<?php
session_start();

include '../../connection.php';

if (isset($_POST['submit'])) {
    // Sanitize and validate the input data
    $patronName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $bookTitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

    if (!empty($patronName) && !empty($bookTitle)) {
        try {
            // Check if the patron exists
            $patronStmt = $pdo->prepare("SELECT patrons_id FROM patrons_inperson WHERE CONCAT_WS(' ', firstname, middlename, lastname, suffix) = :patronName");
            $patronStmt->bindParam(':patronName', $patronName);
            $patronStmt->execute();
            $patron = $patronStmt->fetch(PDO::FETCH_ASSOC);

            // Check if the book exists
            $bookStmt = $pdo->prepare("SELECT book_id FROM books WHERE title = :bookTitle");
            $bookStmt->bindParam(':bookTitle', $bookTitle);
            $bookStmt->execute();
            $book = $bookStmt->fetch(PDO::FETCH_ASSOC);

            if ($patron && $book) {
                $patronId = $patron['patrons_id'];
                $bookId = $book['book_id'];

                // Prepare the SQL statement to insert the borrowing record
                $stmt = $pdo->prepare("INSERT INTO borrow (book_id, patron_id, borrow_date) VALUES (:book_id, :patron_id, NOW())");

                // Bind parameters
                $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
                $stmt->bindParam(':patron_id', $patronId, PDO::PARAM_INT);

                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = 'Borrowing record added successfully.';
                    $_SESSION['success_display'] = 'flex';
                } else {
                    $_SESSION['error_message'] = 'Failed to add borrowing record.';
                    $_SESSION['success_display'] = 'flex';
                }
            } else {
                $_SESSION['error_message'] = 'Invalid patron name or book title.';
                $_SESSION['success_display'] = 'flex';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add borrowing record. Error: ' . $e->getMessage();
        }

        header('Location: ../borrow.php'); // Redirect to the relevant page
        exit();
    } else {
        $_SESSION['error_message'] = 'Patron name and book title cannot be empty.';
        header('Location: ../borrow.php'); // Redirect to the relevant page
        exit();
    }
}
?>
