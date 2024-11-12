<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Acitivity Logs</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

session_start();

include '../connection.php';


include 'functions/fetch_book.php';
$bookList = getBookList($pdo);

include 'functions/fetch_circulation.php';
$bookCirculation = getCirculation($pdo);

include 'functions/fetch_patrons.php';
$patronsName = getPatronsNames($pdo);

?>

<body>
    <div class="wrapper">

        <div class="container-top">

            <?php include 'container-top.php'; ?>

        </div>


        <div class="container-content">

            <div class="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="body">
                <div class="row">
                    <div class="title-26px">
                        Librarians Activity Logs
                    </div>
                </div>

                <div class="container-white">

                    <div class="container-success" id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                                            unset($_SESSION['success_display']); ?>;">
                        <div class="container-success-description">
                            <?php if (isset($_SESSION['success_message'])) {
                                echo $_SESSION['success_message'];
                                unset($_SESSION['success_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>
                    </div>


                    <div class="container-error" id="container-error-transaction" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                    unset($_SESSION['error_display']); ?>;">
                        <div class="container-error-description">
                            <?php if (isset($_SESSION['error_message'])) {
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-error-close" onclick="closeErrorTransactionStatus()">&times;</button>
                    </div>


                    <div class="row row-between">

                        <div class="container-input-30">
                            <select class="input-text" id="activity" name="activity" onchange="filterSections()" required>
                                <option value="Transactions" selected>Transactions</option>
                                <option value="Books">Books</option>
                                <option value="Patrons">Patrons</option>
                            </select>
                        </div>
                    </div>


                    <!-- transaction table -->
                    <div id="transaction" style="display: flex; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search" onkeyup="searchTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="transaction-table-container">
                            <!-- transaction table here -->

                            transaction changes
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info"></div>
                            <div class="pagination" id="pagination"></div>
                        </div>

                    </div>


                    <!-- books table -->
                    <div id="books-activity" style="display: none; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-books" onkeyup="searchPendingTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-books" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="book-table-container">
                            <!-- books changes here -->

                            books changes
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-books"></div>
                            <div class="pagination" id="pagination-books"></div>
                        </div>

                    </div>




                    <!-- patrons table -->
                    <div id="patrons-activity" style="display: none; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-patrons" onkeyup="searchBorrowedTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-patrons" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="patrons-table-container">
                            <!-- patrons changes here -->
                            patrons changes
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-patrons"></div>
                            <div class="pagination" id="pagination-patrons"></div>
                        </div>

                    </div>







                </div>

            </div>


            <?php include 'modal/add_borrow_modal.php'; ?>
            <?php include 'modal/edit_transactions_modal.php'; ?>


        </div>
</body>

</html>



<!-- modal -->
<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('show');

    }

    function saveChanges() {
        closeAddModal();
    }
</script>







<script src="js/table-transaction-activity.js"></script>
<script src="js/table-book-activity.js"></script>
<script src="js/table-patron-activity.js"></script>


<script>
    // Automatically display 'Transactions' section on page load
    window.onload = function() {
        document.getElementById('transaction').style.display = 'flex';
    };

    function filterSections() {
        // Get the selected value from the dropdown
        const selectedOption = document.getElementById('activity').value;

        // Hide all sections initially
        document.getElementById('transaction').style.display = 'none';
        document.getElementById('books-activity').style.display = 'none';
        document.getElementById('patrons-activity').style.display = 'none';

        // Show the selected section
        if (selectedOption === 'Transactions') {
            document.getElementById('transaction').style.display = 'flex';
        } else if (selectedOption === 'Books') {
            document.getElementById('books-activity').style.display = 'flex';
        } else if (selectedOption === 'Patrons') {
            document.getElementById('patrons-activity').style.display = 'flex';
        }
    }
</script>







<script src="js/close-status.js"></script>


<script>
    const patronsName = <?php echo json_encode($patronsName); ?>;
</script>

<script src="js/autocomplete-patrons.js"></script>

<!-- <script>
    const bookList = <?php echo json_encode($bookList); ?>;
</script>
<script src="js/autocomplete-book-list.js"></script> -->

<script>
    const bookCirculation = <?php echo json_encode($bookCirculation); ?>;
</script>
<script src="js/autocomplete-book-circulation.js"></script>