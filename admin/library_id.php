<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrons Library ID</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';
include 'functions/fetch_patrons_library_id.php';

$patrons = getPatronsIDInfo($pdo);


include 'functions/fetch_patrons.php';
$patronsName = getPatronsNames($pdo);


include 'functions/fetch_guarantors.php';
$guarantorsName = getGuarantorsNames($pdo);
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
                        Patrons Library ID
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
                                            <div class="column-title">Patrons Name</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Guarantor Name</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Date Issued</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Valid Until</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th>
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($patrons)) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="no-result">
                                                <div class="no-result-image">
                                                    <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                                </div>
                                                <p>No results found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($patrons as $patron) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($patron['patron_firstname'] . ' ' . $patron['patron_lastname'] . ' ' . $patron['patron_suffix']); ?></td>
                                            <td><?php echo htmlspecialchars($patron['guarantor_firstname'] . ' ' . $patron['guarantor_lastname'] . ' ' . $patron['guarantor_suffix']); ?></td>
                                            <td><?php echo htmlspecialchars($patron['date_issued']); ?></td>
                                            <td><?php echo htmlspecialchars($patron['valid_until']); ?></td>
                                            <td>
                                                <div class="td-center">
                                                <div class="td-center">
    <div class="button-view" onclick="openViewModal(
        <?php echo addslashes($patron['patrons_id']); ?>, 
        '<?php echo addslashes($patron['patron_firstname'] . ' ' . $patron['patron_middlename'] . ' ' . $patron['patron_lastname']); ?>', 
        '<?php echo addslashes($patron['patron_address']); ?>', 
        '<?php echo addslashes($patron['patron_company_name']); ?>'
    )">
        <img src="../images/view-white.png" class="image">
    </div>
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

        <?php include 'modal/add_patrons_library_id_modal.php'; ?>
        <?php include 'modal/view_patrons_library_id_modal.php'; ?>


    </div>
</body>

</html>



<script>
    let sortDirections = [0, 0, 0, 0];
    const NO_RESULT_COLSPAN = 5;
</script>
<script src="js/table.js"></script>

<script src="js/close-status.js"></script>


<script>
    const patronsName = <?php echo json_encode($patronsName); ?>;
</script>

<script src="js/autocomplete-patrons.js"></script>


<script>
    const guarantorsName = <?php echo json_encode($guarantorsName); ?>;
</script>

<script src="js/autocomplete-guarantors.js"></script>