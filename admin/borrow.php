<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

session_start();

include '../connection.php';

include 'functions/fetch_borrowed_books.php';
$borrowedBooks = getBorrowedBooks($pdo);

include 'functions/fetch_book.php';
$bookTitles = getBookTitles($pdo);

include 'functions/fetch_patrons.php';
$patronsName = getPatronsNames($pdo);

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
                        Borrow Books
                    </div>
                </div>

                <div class="container-white">

                    <div class="row row-right">
                        <button class="button-borrow" onclick="openAddModal()">
                            &#43; Borrow
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
                                            <div class="column-title">Date</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Acc No.</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Class No.</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Book Title</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Borrower</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(4)">
                                        <div class="row row-between">
                                            <div class="column-title">Status</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($borrowedBooks as $book) { ?>
                                    <tr>
                                        <td><?php echo $book['borrow_date']; ?></td>
                                        <td><?php echo $book['acc_number']; ?></td>
                                        <td><?php echo $book['class_number']; ?></td>
                                        <td><?php echo $book['title']; ?></td>
                                        <td><?php echo $book['name']; ?></td>
                                        <td>
                                            <center>
                                                <div class="status <?php echo strtolower($book['status']); ?>">
                                                    <?php echo $book['status']; ?>
                                                </div>
                                            </center>
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

<script src="js/sidebar.js"></script>
<script src="js/table-borrow.js"></script>

<script>
    const patronsName = <?php echo json_encode($patronsName); ?>;
</script>

<script src="js/autocomplete-patrons.js"></script>

<script>
    const bookTitles = <?php echo json_encode($bookTitles); ?>;
</script>
<script src="js/autocomplete-book-title.js"></script>