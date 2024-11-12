<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

session_start();

include '../connection.php';

include 'functions/fetch_book.php';
$bookList = getBookList($pdo);

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
                        Borrow Books
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


                    <div class="container-error" id="container-error-borrow" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                unset($_SESSION['error_display']); ?>;">
                        <div class="container-error-description">
                            <?php if (isset($_SESSION['error_message'])) {
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-error-close" onclick="closeErrorBorrowStatus()">&times;</button>
                    </div>



                    <div class="row row-right">
                        <button class="button-borrow" onclick="openAddModal()">
                            &#43; Borrow
                        </button>
                    </div>

                    <div class="row row-between">

                        <div style="display: flex; flex-direction:row; align-items: center">

                            <div class="container-search">
                                <input type="text" id="search" placeholder="Search" style="border:none; height:40px; width: 200px; font-size: 14px;" onclick="this.style.outline='none';">
                                <div class="container-image-clear" id="clear-button">
                                    <img src="../images/clear-black.png" class="image">
                                </div>
                            </div>
                            <div class="container-search-icon">
                                <div class="container-image-search" id="search-button">
                                    <img src="../images/search-white.png" class="image">
                                </div>
                            </div>

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

                    <div class="row">

                        <table id="table">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">
                                        <div class="row row-between">
                                            <div class="column-title">Borrow ID</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Borrow Date</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Acc No.</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Class No.</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(4)">
                                        <div class="row row-between">
                                            <div class="column-title">Book Title</div>
                                            <img id="sort-icon-4" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(5)">
                                        <div class="row row-between">
                                            <div class="column-title">Borrower</div>
                                            <img id="sort-icon-5" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(6)">
                                        <div class="row row-between">
                                            <div class="column-title">Status</div>
                                            <img id="sort-icon-6" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td colspan="7">
                                        <div class="no-result">
                                            <div class="no-result-image">
                                                <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                            </div>
                                            <p>Use search bar to search for data.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>


                        </table>



                    </div>

                    <div class="row row-between">
                        <div class="entry-info" id="entry-info"></div>
                        <div class="pagination" id="pagination"></div>
                    </div>

                </div>

            </div>

        </div>


        <?php include 'modal/add_borrow_modal.php'; ?>


    </div>
</body>

</html>




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



<!-- table -->
<script>
    let sortDirections = [0, 0, 0, 0, 0, 0];

    function fetchData(searchQuery) {
        fetch('functions/fetch_borrow_table.php?search=' + encodeURIComponent(searchQuery))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert('Error fetching data. Please try again later.');
            });
    }


    function updateTable(borrow) {
        const tbody = document.querySelector("#table tbody");
        tbody.innerHTML = ""; // Clear existing rows

        if (borrow.length === 0) {
            // Check if the search bar or filter was used but no results were found
            const searchActive = document.getElementById("search").value.trim() !== "";

            // Show the appropriate no result message
            if (searchActive) {
                tbody.innerHTML = `<tr>
                                    <td colspan="7">
                                        <div class="no-result">
                                            <div class="no-result-image">
                                                <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                            </div>
                                            <p>No results found.</p>
                                        </div>
                                    </td>
                                </tr>`;
            } else {
                tbody.innerHTML = `<tr>
                                    <td colspan="7">
                                        <div class="no-result">
                                            <div class="no-result-image">
                                                <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                            </div>
                                            <p>Use search bar to search for data.</p>
                                        </div>
                                    </td>
                                </tr>`;
            }

            filteredRows = [];
            originalRows = []; // Reset originalRows if no borrows found
            
        } else {
            borrow.forEach(borrow => {
                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${borrow.borrow_id}</td>
                        <td>${borrow.borrow_date}</td>
                        <td>${borrow.acc_number}</td>
                        <td>${borrow.class_number}</td>
                        <td>${borrow.title}</td>
                        <td>${borrow.firstname} ${borrow.middlename ? borrow.middlename + ' ' : ''}${borrow.lastname} ${borrow.suffix ? borrow.suffix : ''}</td>
                        <td>
                            <center>
                        	<div class="status ${borrow.status.toLowerCase()}">
                                 	${borrow.status}
                       		</div>
                   	    </center>
                        </td>`;
                tbody.appendChild(row);
            });
            filteredRows = Array.from(tbody.getElementsByTagName('tr')); // Update filteredRows with new data
            originalRows = filteredRows.slice(); // Save original rows for reference
        }

        currentPage = 1; // Reset to first page after fetching new results
        displayTable(); // Display the table with pagination
    }
</script>

<script src="js/table-hidden.js"></script>



<script src="js/close-status.js"></script>


<script>
    const patronsName = <?php echo json_encode($patronsName); ?>;
</script>

<script src="js/autocomplete-patrons.js"></script>

<script>
    const bookList = <?php echo json_encode($bookList); ?>;
</script>
<script src="js/autocomplete-book-list.js"></script>