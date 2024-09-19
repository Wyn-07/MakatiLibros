<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Library Patrons</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';
include 'functions/fetch_patrons.php';

$patronsOnline = getPatrons($pdo);
?>

<body>
    <div class="wrapper">
        <div class="container-top">

            <div class="row row-between">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="../images/makati-logo.png" class="image">
                    </div>
                    MakatiLibros
                </div>


                <div class="row-auto container-profile" onclick="openEditModal()">
                    <div class="container-round profile">
                        <img src="../images/sample-profile.jpg" class="image">
                    </div>
                    Wyn Bacolod
                </div>

            </div>

        </div>

        <div class="container-content">

            <div class="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="body">
                <div class="row">
                    <div class="title-26px">
                        Online Library Patrons
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
                                            <div class="column-title">Age</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Contact</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Last Visit</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th>
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($patronsOnline as $patrons) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($patrons['firstname'] . ' ' . $patrons['lastname'] . ' ' . $patrons['suffix']); ?></td>
                                        <td><?php echo htmlspecialchars($patrons['age']); ?></td>
                                        <td><?php echo htmlspecialchars($patrons['contact']); ?></td>
                                        <td><?php ?></td>
                                        <td>
                                            <div class="td-center">
                                                <div class="button-edit"
                                                    onclick="openEditModal(
                                                                <?php echo $patrons['patrons_id']; ?>, 
                                                                '<?php echo addslashes($patrons['firstname']); ?>',
                                                                '<?php echo addslashes($patrons['middlename']); ?>',
                                                                '<?php echo addslashes($patrons['lastname']); ?>',
                                                                '<?php echo addslashes($patrons['suffix']); ?>',
                                                                '<?php echo addslashes($patrons['birthdate']); ?>',
                                                                <?php echo $patrons['age']; ?>,
                                                                '<?php echo addslashes($patrons['gender']); ?>',
                                                                '<?php echo addslashes($patrons['contact']); ?>',
                                                                '<?php echo addslashes($patrons['address']); ?>',
                                                                '<?php echo addslashes($patrons['interest']); ?>',
                                                                '<?php echo addslashes($patrons['email']); ?>',
                                                                '<?php echo addslashes($patrons['password']); ?>'
                                                            )">

                                                    <img src="../images/edit-white.png" class="image">
                                                </div>
                                                <div class="button-view"
                                                    onclick="openViewModal(
                                                                <?php echo $patrons['patrons_id']; ?>, 
                                                                '<?php echo addslashes($patrons['firstname']); ?>',
                                                                '<?php echo addslashes($patrons['middlename']); ?>',
                                                                '<?php echo addslashes($patrons['lastname']); ?>',
                                                                '<?php echo addslashes($patrons['suffix']); ?>',
                                                                '<?php echo addslashes($patrons['birthdate']); ?>',
                                                                <?php echo $patrons['age']; ?>,
                                                                '<?php echo addslashes($patrons['gender']); ?>',
                                                                '<?php echo addslashes($patrons['contact']); ?>',
                                                                '<?php echo addslashes($patrons['address']); ?>',
                                                                '<?php echo addslashes($patrons['interest']); ?>',
                                                                '<?php echo addslashes($patrons['email']); ?>',
                                                                '<?php echo addslashes($patrons['password']); ?>'
                                                            )">
                                                    <img src="../images/view-white.png" class="image">
                                                </div>
                                                <div class="button-delete" onclick="openDeleteModal()">
                                                    <img src="../images/delete-white.png" class="image">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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

        <?php include 'modal/add_patrons_modal.php'; ?>
        <?php include 'modal/edit_patrons_modal.php'; ?>
        <?php include 'modal/view_patrons_modal.php'; ?>


    </div>
</body>

</html>


<script src="js/sidebar.js"></script>
<script src="js/table-reader.js"></script>

<script src="js/close-status.js"></script>