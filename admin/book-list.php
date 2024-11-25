<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php
session_start();

include '../connection.php';


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
                        Book List
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

                    <div class="row" id="book-table-container">

                    </div>

                    <div class="row row-between">
                        <div class="entry-info" id="entry-info"></div>
                        <div class="pagination" id="pagination"></div>
                    </div>

                </div>

            </div>

        </div>


        <?php include 'modal/add_book_modal.php'; ?>

        <?php include 'modal/edit_book_modal.php'; ?>

        <?php include 'modal/delete_book_modal.php'; ?>



        <!-- <div id="deleteModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Delete | Book
                    </div>
                    <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div style="text-align: center; margin-bottom: 10px;">
                            Are you sure you want to delete?
                        </div>


                        <div class="row row-center">
                            <button name="cancel" class="button-cancel">No</button>
                            <button type="submit" name="submit" class="button-submit">Yes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> -->

    </div>
</body>

</html>




<script src="js/table-book.js"></script>

<script src="js/close-status.js"></script>


<script>
    const authorsList = <?php echo json_encode($authorList); ?>;
</script>
<script src="js/autocomplete-author-name.js"></script>


<script>
    const categoryList = <?php echo json_encode($categoryList); ?>;
</script>
<script src="js/autocomplete-category-name.js"></script>