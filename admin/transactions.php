<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>

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
                        Book Transactions
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

                        <div>
                            <!-- Filter Buttons -->
                            <div class="row-filter">
                                <button class="button-filter active" onclick="filterTransactions('all', this)">All</button>
                                <button class="button-filter" onclick="filterTransactions('pending', this)">Pending</button>
                                <button class="button-filter" onclick="filterTransactions('accepted', this)">Accepted</button>
                                <button class="button-filter" onclick="filterTransactions('borrowed', this)">Borrowed</button>
                                <button class="button-filter" onclick="filterTransactions('returned', this)">Returned</button>
                            </div>
                        </div>


                        <!-- <button class="button-borrow" onclick="openAddModal()">
                            &#43; Borrow
                        </button> -->
                    </div>


                    <!-- transaction all table -->
                    <div id="transactionAll" style="display: flex; flex-direction:column; gap:20px">

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
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info"></div>
                            <div class="pagination" id="pagination"></div>
                        </div>

                    </div>


                    <!-- transaction pending table -->
                    <div id="transactionPending" style="display: none; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-pending" onkeyup="searchPendingTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-pending" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="pending-table-container">
                            <!-- transaction pending here -->
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-pending"></div>
                            <div class="pagination" id="pagination-pending"></div>
                        </div>

                    </div>



                    <!-- transaction accepted table -->
                    <div id="transactionAccepted" style="display: none; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-accepted" onkeyup="searchAcceptedTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-accepted" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="accepted-table-container">
                            <!-- transaction accepted here -->
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-accepted"></div>
                            <div class="pagination" id="pagination-accepted"></div>
                        </div>

                    </div>



                    <!-- transaction borrowing table -->
                    <div id="transactionBorrowed" style="display: none; flex-direction:column; gap:20px">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-borrowed" onkeyup="searchBorrowedTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-borrowed" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="borrowed-table-container">
                            <!-- transaction borrowed here -->
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-borrowed"></div>
                            <div class="pagination" id="pagination-borrowed"></div>
                        </div>

                    </div>


                    <!-- transaction returned table -->
                    <div id="transactionReturned" style="display: none; flex-direction:column; gap:20px ">

                        <div class="row row-between">

                            <div>
                                <label for="search">Search: </label>
                                <input class="table-search" type="text" id="search-returned" onkeyup="searchReturnedTable()">
                            </div>

                            <div>
                                <label for="entries">Show </label>
                                <select class="table-select" id="entries-returned" onchange="changeEntries()">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <label for="entries"> entries</label>
                            </div>

                        </div>


                        <div class="row" id="returned-table-container">
                            <!-- transaction returned here -->
                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info-returned"></div>
                            <div class="pagination" id="pagination-returned"></div>
                        </div>

                    </div>


                </div>

            </div>


            <?php include 'modal/add_borrow_modal.php'; ?>
            <?php include 'modal/edit_transactions_modal.php'; ?>
            <?php include 'modal/add_delinquent_modal.php'; ?>



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







<script src="js/table-transaction.js"></script>
<script src="js/table-pending.js"></script>
<script src="js/table-accepted.js"></script>
<script src="js/table-borrowed.js"></script>
<script src="js/table-returned.js"></script>


<script>
    // Automatically display 'All' on page load
    window.onload = function() {
        document.getElementById('transactionAll').style.display = 'flex';
    };

    function filterTransactions(filter, button) {
        // Hide all transaction divs
        document.getElementById('transactionAll').style.display = 'none';
        document.getElementById('transactionPending').style.display = 'none';
        document.getElementById('transactionAccepted').style.display = 'none';
        document.getElementById('transactionBorrowed').style.display = 'none';
        document.getElementById('transactionReturned').style.display = 'none';

        // Show the corresponding div based on the filter
        if (filter === 'all') {
            document.getElementById('transactionAll').style.display = 'flex';
        } else if (filter === 'pending') {
            document.getElementById('transactionPending').style.display = 'flex';
        } else if (filter === 'accepted') {
            document.getElementById('transactionAccepted').style.display = 'flex';
        }else if (filter === 'borrowed') {
            document.getElementById('transactionBorrowed').style.display = 'flex';
        } else if (filter === 'returned') {
            document.getElementById('transactionReturned').style.display = 'flex';
        }

        // Remove 'active' class from all buttons
        let buttons = document.querySelectorAll('.button-filter');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // Add 'active' class to the clicked button
        button.classList.add('active');
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