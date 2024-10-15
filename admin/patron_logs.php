<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrons</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';
include 'functions/fetch_patron_logs.php';

$patronLogs = getPatronLogs($pdo);

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

                        <div>
                            <label for="search">Search: </label>
                            <input class="table-search" type="text" id="search" oninput="searchTable()">
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
                                <?php if (empty($patronLogs)) { ?>
                                    <tr>
                                        <td colspan="6">
                                            <div class="no-result">
                                                <div class="no-result-image">
                                                    <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                                </div>
                                                <p>No results found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($patronLogs as $log) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($log['log_date']); ?></td>
                                            <td><?php echo htmlspecialchars($log['firstname'] . ' ' . $log['lastname'] . ' ' . $log['suffix']); ?></td>
                                            <td><?php echo htmlspecialchars($log['age']); ?></td>
                                            <td><?php echo htmlspecialchars($log['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($log['purpose']); ?></td>
                                            <td><?php echo htmlspecialchars($log['barangay']); ?></td>
                                            <td><?php echo htmlspecialchars($log['city']); ?></td>
                                            <td>
                                                <div class="td-center">
                                                    <div class="button-edit" onclick="openEditModal(
                                <?php echo $log['log_id']; ?>, 
                                '<?php echo addslashes($log['log_date']); ?>',
                                '<?php echo addslashes($log['firstname']); ?>',
                                '<?php echo addslashes($log['middlename']); ?>',
                                '<?php echo addslashes($log['lastname']); ?>',
                                '<?php echo addslashes($log['suffix']); ?>',
                                <?php echo $log['age']; ?>,
                                '<?php echo addslashes($log['gender']); ?>',
                                '<?php echo addslashes($log['barangay']); ?>',
                                '<?php echo addslashes($log['city']); ?>',
                                '<?php echo addslashes($log['purpose']); ?>',
                                '<?php echo addslashes($log['sector']); ?>',
                                '<?php echo addslashes($log['sector_details']); ?>'
                            )">
                                                        <img src="../images/edit-white.png" class="image">
                                                    </div>
                                                   
                                                    <div class="button-delete" onclick="openDeleteModal()">
                                                        <img src="../images/delete-white.png" class="image">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
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


<script>
    let sortDirections = [0, 0, 0, 0, 0, 0, 0];
    const NO_RESULT_COLSPAN = 8;
</script>
<script src="js/table.js"></script>
<script src="js/close-status.js"></script>


<script>
    const patronsBasicInfo = <?php echo json_encode($patronsBasicInfo); ?>;
</script>

<script src="js/autocomplete-patrons-basic-info.js"></script>

