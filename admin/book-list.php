<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

include '../connection.php';

include 'functions/fetch_book.php';
$bookList = getBookList($pdo);



include 'functions/fetch_author_name.php';
$authorName = getAuthorName($pdo);


include 'functions/fetch_category_name.php';
$categoryName = getCategoryName($pdo);



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
                        Book List
                    </div>
                </div>

                <div class="container-white">

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
                                    <th onclick="sortTable(0)">
                                        <div class="row row-between">
                                            <div class="column-title">Class Number</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
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
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Category</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(0)">
                                        <div class="row row-between">
                                            <div class="column-title">Copyright</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th style="width:100px">
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($bookList as $book) { ?>
                                    <tr>
                                        <td><?php echo $book['acc_number']; ?></td>
                                        <td><?php echo $book['class_number']; ?></td>
                                        <td><?php echo $book['title']; ?></td>
                                        <td><?php echo $book['author_name']; ?></td>
                                        <td><?php echo $book['category_name']; ?></td>
                                        <td><?php echo $book['copyright']; ?></td>
                                        <td>
                                            <center>
                                                <div class="button-edit">
                                                    <img src="../images/edit-white.png" class="image">
                                                </div>
                                                <div class="button-delete">
                                                    <img src="../images/delete-white.png" class="image">
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














        <div id="addModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Add | Book
                    </div>
                    <span class="modal-close" onclick="closeAddModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input-100">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" class="input-text" autocomplete="off" required>
                        </div>


                        <div class="container-input-100">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="author">Author</label>
                            <input type="text" id="author" name="author" class="input-text" autocomplete="off" required>
                        </div>


                        <div class="container-input">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>

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








        <div id="editModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Edit | Book
                    </div>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="category">Category </label>
                            <select class="select-text" id="category">
                                <option value="a">a</option>
                                <option value="b">b</option>
                                <option value="c">c</option>
                                <option value="d">d</option>
                            </select>
                        </div>

                        <div class="container-input">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="author">Author </label>
                            <select class="select-text" id="author">
                                <option value="a">a</option>
                                <option value="b">b</option>
                                <option value="c">c</option>
                                <option value="d">d</option>
                            </select>
                        </div>

                        <div class="container-input">
                            <label for="publisher">Publisher</label>
                            <input type="text" name="publisher" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>









        <div id="deleteModal" class="modal">
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
        </div>

    </div>
</body>

</html>


<script src="js/sidebar.js"></script>
<script src="js/table-book-list.js"></script>
<script src="js/add-modal.js"></script>
<script src="js/edit-modal-book-list.js"></script>
<script src="js/delete-modal-book-list.js"></script>


<script>
    const categoryNames = <?php echo json_encode($categoryName); ?>;
</script>
<script src="js/autocomplete-category-name.js"></script>

<script>
    const authorsNames = <?php echo json_encode($authorName); ?>;
</script>
<script src="js/autocomplete-author-name.js"></script>