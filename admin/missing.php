<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';

include 'functions/fetch_missing.php';
$missingList = getMissingList($pdo);

include 'functions/fetch_book.php';
$bookList = getBookList($pdo);

include 'functions/fetch_author.php';
$authorList = getAuthorList($pdo);


include 'functions/fetch_category.php';
$categoryList = getCategoryList($pdo);



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
                        Missing Book
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
                                            <div class="column-title">Acc Number</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Class Number</div>
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
                                            <div class="column-title">Author</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(4)">
                                        <div class="row row-between">
                                            <div class="column-title">Category</div>
                                            <img id="sort-icon-4" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(5)">
                                        <div class="row row-between">
                                            <div class="column-title">Copyright</div>
                                            <img id="sort-icon-5" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th style="width:100px">
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($missingList)) { ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="no-result">
                                                <div class="no-result-image">
                                                    <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                                                </div>
                                                <p>No Data</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($missingList as $book) { ?>
                                        <tr>
                                            <td><?php echo $book['acc_number']; ?></td>
                                            <td><?php echo $book['class_number']; ?></td>
                                            <td><?php echo $book['title']; ?></td>
                                            <td><?php echo $book['author_name']; ?></td>
                                            <td><?php echo $book['category_name']; ?></td>
                                            <td><?php echo $book['copyright']; ?></td>
                                            <td>
                                                <center>
                                                    <div class="button-view" onclick="openViewModal(
                                                                '<?php echo addslashes($book['missing_id']); ?>', 
                                                                '<?php echo addslashes($book['acc_number']); ?>', 
                                                                '<?php echo addslashes($book['class_number']); ?>', 
                                                                '<?php echo addslashes($book['title']); ?>', 
                                                                '<?php echo addslashes($book['author_name']); ?>', 
                                                                '<?php echo addslashes($book['author_id']); ?>', 
                                                                '<?php echo addslashes($book['category_name']); ?>', 
                                                                '<?php echo addslashes($book['category_id']); ?>', 
                                                                '<?php echo addslashes($book['copyright']); ?>',
                                                                '<?php echo addslashes($book['image']); ?>'
                                                            )">
                                                        <img src="../images/view-white.png" class="image">
                                                    </div>
                                                </center>
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


        <?php include 'modal/add_missing_modal.php'; ?>

        <?php include 'modal/view_missing_modal.php'; ?>


    </div>
</body>

</html>

<script>
    let sortDirections = [0, 0, 0, 0, 0, 0];
    const NO_RESULT_COLSPAN = 7;
</script>
<script src="js/table.js"></script>

<script src="js/close-status.js"></script>



<script>
    const bookList = <?php echo json_encode($bookList); ?>;
</script>
<script src="js/autocomplete-book-list-full.js"></script>