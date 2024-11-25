<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trails</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

session_start();

include '../connection.php';

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
                        Audit Trails
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



                    <!-- transaction table -->
                    <div style="display: flex; flex-direction:column; gap:20px">

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


                        <div class="row" id="audit-table-container">
                            <!-- transaction table here -->

                        </div>

                        <div class="row row-between">
                            <div class="entry-info" id="entry-info"></div>
                            <div class="pagination" id="pagination"></div>
                        </div>

                    </div>


                </div>

            </div>


            <?php include 'modal/view_audit_modal.php'; ?>


        </div>
</body>

</html>




<script src="js/table-audit.js"></script>

<script src="js/close-status.js"></script>
