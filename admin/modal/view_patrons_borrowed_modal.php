<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    header('Content-Type: application/json'); // Ensure the response is JSON

    // Use prepared statements for security
    $patronId = $conn->real_escape_string($_GET['patron_id']);

    // Query to get book title and image for the patron
    $query = "
SELECT books.title, books.image 
FROM books
INNER JOIN borrow ON books.book_id = borrow.book_id
WHERE borrow.patrons_id = ?
AND borrow.status = 'Borrowed'
";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $patronId); // Bind the parameter properly
        $stmt->execute();
        $result = $stmt->get_result();


        if (!$result) {
            // If the query fails, return the error message in JSON format
            echo json_encode(["error" => "SQL Error: {$conn->error}"]);
            exit;
        }

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        // Return the books as JSON
        echo json_encode($books);
        $stmt->close();
    }
}

$conn->close();
?>


<style>
    .container-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .book-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .book-image {
        width: 50px;
        height: auto;
    }
</style>

<div id="viewBooksModal" class="modal">
    <div class="modal-content-big">
        <div class="row row-between">
            <div class="title-26px">
                View Patron Borrowed Books
            </div>
            <span class="modal-close" onclick="closeViewBooksModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-editpatron" style="display: none">
            <div class="container-error-description" id="message-editpatron"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditPatronsStatus()">&times;</button>
        </div>

        <input type="text" id="editPatronIdBook" placeholder="Enter Patron ID" readonly>

        <div class="container-form">
            <!-- Books will be displayed here -->
        </div>
    </div>
</div>

<script>
    // Listen for back/forward browser navigation (popstate)
    window.addEventListener('popstate', function(event) {
        const urlParams = new URLSearchParams(window.location.search);
        const patronId = urlParams.get('patron_id');

        if (patronId) {
            // Fetch books for the patron if patron_id exists
            fetchBorrowedBooks(patronId);
            
            // Open the modal if it isn't already open
            const modal = document.getElementById('viewBooksModal');
            if (!modal.classList.contains('show')) {
                modal.classList.add('show');
            }
        } else {
            // Close the modal if patron_id is removed
            closeViewBooksModal();
        }
    });

    // Open the modal and update the URL
    function openViewBooksModal(element) {
        const patronId = decodeURIComponent(element.getAttribute('data-patrons-id'));

        // Show the modal
        const modal = document.getElementById('viewBooksModal');
        modal.classList.add('show');

        // Update the modal fields
        const patronInput = document.getElementById('editPatronIdBook');
        patronInput.value = patronId;

        // Push state to update the URL
        history.pushState({ patronId: patronId }, `Patron ${patronId}`, `?patron_id=${patronId}`);

        // Fetch the borrowed books
        fetchBorrowedBooks(patronId);
    }

    // Close the modal and optionally revert the URL
    function closeViewBooksModal() {
        const modal = document.getElementById('viewBooksModal');
        modal.classList.remove('show');

        // Remove the patron_id from the URL
        history.pushState({}, document.title, window.location.pathname);
    }

    // Fetch borrowed books for a specific patron
    function fetchBorrowedBooks(patronId) {
        const container = document.querySelector('.container-form');

        // Clear previous content and show loading message
        container.innerHTML = 'Loading...';

        // Make the fetch request
        fetch(`/MakatiLibros/admin/patrons.php?patron_id=${patronId}`)
            .then(response => response.json()) // Parse JSON directly
            .then(books => {
                container.innerHTML = books.length 
                    ? books.map(book => `
                        <div class="book-item">
                            <img src="${book.image}" alt="${book.title}" class="book-image">
                            <p>${book.title}</p>
                        </div>
                    `).join('') 
                    : '<p>No books found.</p>';
            })
            .catch(error => {
                console.error('Error fetching books:', error);
                container.innerHTML = '<p>Error fetching books. Please try again later.</p>';
            });
    }
</script>


<script src="js/close-status.js"></script>