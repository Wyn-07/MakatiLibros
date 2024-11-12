<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>


<?php
session_start();

include '../connection.php';


include 'functions/fetch_pending_books.php';
$pendingBooks = getPendingBooks($pdo);

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
                        Pending Books
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


                    <div class="container-error" id="container-error-return" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                unset($_SESSION['error_display']); ?>;">
                        <div class="container-error-description">
                            <?php if (isset($_SESSION['error_message'])) {
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-error-close" onclick="closeErrorReturnStatus()">&times;</button>
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
                                            <div class="column-title">Acc No.</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Class No.</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Book Title</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Borrower</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th>
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td colspan="5">
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



        <?php include 'modal/edit_pending_modal.php'; ?>




    </div>
</body>

</html>


<!-- table -->
<script>
    let sortDirections = [0, 0, 0, 0];

    function fetchData(searchQuery) {
        fetch('functions/fetch_pending_table.php?search=' + encodeURIComponent(searchQuery))
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



    function updateTable(pending) {
        const tbody = document.querySelector("#table tbody");
        tbody.innerHTML = ""; // Clear existing rows

        if (pending.length === 0) {
            // Check if the search bar or filter was used but no results were found
            const searchActive = document.getElementById("search").value.trim() !== "";

            // Show the appropriate no result message
            if (searchActive) {
                tbody.innerHTML = `<tr>
                                    <td colspan="5">
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
                                    <td colspan="5">
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
            originalRows = []; // Reset originalRows if no pending found
        } else {
            pending.forEach(pending => {
                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${pending.acc_number}</td>
                        <td>${pending.class_number}</td>
                        <td>${pending.title}</td>
                        <td>${pending.firstname} ${pending.middlename ? pending.middlename + ' ' : ''}${pending.lastname} ${pending.suffix ? pending.suffix : ''}</td>
                        <td>
                             <div class="td-center">
                               <div class="button-edit" data-borrow-id="${encodeURIComponent(pending.borrow_id)}"  onclick="openEditModal(this)">
                                   <img src="../images/edit-white.png" class="image">
                              </div>
                           </div>
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