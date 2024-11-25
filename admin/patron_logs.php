<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrons</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';

include 'functions/fetch_patrons.php';
$patronsBasicInfo = getPatronsBasicInfo($pdo);
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
                        Patron Logs
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


                    <div class="row row-right">
                        <button class="button-borrow" onclick="openAddModal()">
                            &#43; New
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
                                            <div class="column-title">Log Date</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Patron Name</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Age</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Gender</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(4)">
                                        <div class="row row-between">
                                            <div class="column-title">Purpose</div>
                                            <img id="sort-icon-4" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(5)">
                                        <div class="row row-between">
                                            <div class="column-title">Barangay</div>
                                            <img id="sort-icon-5" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(6)">
                                        <div class="row row-between">
                                            <div class="column-title">City</div>
                                            <img id="sort-icon-6" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>

                                    <th>
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8">
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

        <?php include 'modal/add_patron_logs_modal.php'; ?>
        <?php include 'modal/edit_patron_logs_modal.php'; ?>


    </div>
</body>

</html>


<!-- table -->
<script>
    let sortDirections = [0, 0, 0, 0, 0, 0, 0];

    function fetchData(searchQuery) {
        fetch('functions/fetch_patron_logs_table.php?search=' + encodeURIComponent(searchQuery))
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



    function updateTable(logs) {
        const tbody = document.querySelector("#table tbody");
        tbody.innerHTML = ""; // Clear existing rows

        if (logs.length === 0) {
            // Check if the search bar or filter was used but no results were found
            const searchActive = document.getElementById("search").value.trim() !== "";

            // Show the appropriate no result message
            if (searchActive) {
                tbody.innerHTML = `<tr>
                                    <td colspan="8">
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
                                    <td colspan="8">
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
            originalRows = []; // Reset originalRows if no logs found
        } else {
            logs.forEach(logs => {
                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${logs.log_date}</td>
                        <td>${logs.firstname} ${logs.middlename ? logs.middlename + ' ' : ''}${logs.lastname} ${logs.suffix ? logs.suffix : ''}</td>
                        <td>${logs.age}</td>
                        <td>${logs.gender}</td>
                        <td>${logs.purpose}</td>
			            <td>${logs.barangay}</td>
                        <td>${logs.city}</td>
                        <td>
                            <center>
                               <div class="button-edit" 
                                    data-logs-id="${encodeURIComponent(logs.log_id)}"
                                    data-logs-date="${encodeURIComponent(logs.log_date)}"
                                    data-logs-firstname="${encodeURIComponent(logs.firstname)}"
                                    data-logs-middlename="${encodeURIComponent(logs.middlename)}"
                                    data-logs-lastname="${encodeURIComponent(logs.lastname)}"
                                    data-logs-suffix="${encodeURIComponent(logs.suffix)}"
                                    data-logs-age="${encodeURIComponent(logs.age)}"
                                    data-logs-gender="${encodeURIComponent(logs.gender)}"
                                    data-logs-barangay="${encodeURIComponent(logs.barangay)}"
                                    data-logs-city="${encodeURIComponent(logs.city)}"
                                    data-logs-purpose="${encodeURIComponent(logs.purpose)}"
                                    data-logs-sector="${encodeURIComponent(logs.sector)}"
                                    data-logs-sector-details="${encodeURIComponent(logs.sector_details)}"
                                    
                                    onclick="openEditModal(this)">
                                    
                                <img src="../images/edit-white.png" class="image">
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
    const patronsBasicInfo = <?php echo json_encode($patronsBasicInfo); ?>;
</script>

<script src="js/autocomplete-patrons-basic-info.js"></script>