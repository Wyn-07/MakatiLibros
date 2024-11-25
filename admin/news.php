<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

session_start();

include '../connection.php';


include 'functions/fetch_news.php';
$news = getNews($pdo);

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
                        News
                    </div>
                </div>

                <div class="news-contents">

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


                    <div class="container-error" id="container-error-borrow" style="display: <?php echo isset($_SESSION['error_display']) ? $_SESSION['error_display'] : 'none';
                                                                                                unset($_SESSION['error_display']); ?>;">
                        <div class="container-error-description">
                            <?php if (isset($_SESSION['error_message'])) {
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                            } ?>
                        </div>
                        <button type="button" class="button-error-close" onclick="closeErrorBorrowStatus()">&times;</button>
                    </div>



                    <div class="row row-between">
                        <div>
                            <label for="search">Search: </label>
                            <input class="table-search" type="text" id="search" onkeyup="searchTable()">
                        </div>

                        <button class="button-add" type="button" id="add" onclick="openAddModal()">
                            &#43; Add
                        </button>
                    </div>


                    <!-- Not Found Message -->
                    <div id="not-found-message" style="display: none; text-align: center; margin-top: 20px;">
                        <p>No matching news found.</p>
                    </div>


                    <?php foreach ($news as $item) : ?>
                        <div class="news-box-container"
                            onclick="openEditModal(
                                        '<?php echo htmlspecialchars($item['news_id']); ?>', 
                                        '<?php echo htmlspecialchars($item['title']); ?>', 
                                        '<?php echo htmlspecialchars($item['date']); ?>', 
                                        '<?php echo htmlspecialchars($item['description']); ?>', 
                                        '<?php echo htmlspecialchars($item['image']); ?>'
                                    )">
                            <div class="news-image">
                                <img src="../news_images/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="image">
                            </div>

                            <div class="news-title">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </div>

                            <div class="news-date">
                                <?php echo htmlspecialchars($item['date']); ?>
                            </div>

                            <div class="news-description">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>


                    <?php include 'modal/edit_news_modal.php'; ?>


                </div>


            </div>

        </div>


    </div>



    <?php include 'modal/add_news_modal.php'; ?>



</body>

</html>



<script src="js/close-status.js"></script>





<!-- fade when exceed -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const descriptions = document.querySelectorAll('.news-description');

        descriptions.forEach(description => {
            // Check if the content overflows
            if (description.scrollHeight > description.clientHeight) {
                description.classList.add('fade');
            }
        });
    });
</script>



<script>
    const textAreas = [
        document.getElementById("description"),
        document.getElementById("editDescription")
    ];

    textAreas.forEach(textBox => {
        textBox.addEventListener("keydown", function(event) {
            if (event.key === "Enter" || event.keyCode === 13) {
                event.preventDefault();
                const cursorPosition = textBox.selectionStart;
                const text = textBox.value;
                const newText =
                    text.slice(0, cursorPosition) + "<br>\n" + text.slice(cursorPosition);
                textBox.value = newText;
            }
        });
    });
</script>



<script>
    function searchTable() {
        // Get the value from the search input
        const searchInput = document.getElementById('search').value.toLowerCase();

        // Get all news box containers
        const newsBoxes = document.querySelectorAll('.news-box-container');
        let found = false; // Flag to track if any box is visible

        // Loop through each news box
        newsBoxes.forEach(box => {
            // Get the title and description text
            const title = box.querySelector('.news-title').textContent.toLowerCase();
            const description = box.querySelector('.news-description').textContent.toLowerCase();

            // Check if the title or description includes the search input
            if (title.includes(searchInput) || description.includes(searchInput)) {
                box.style.display = 'flex'; // Show the box
                found = true; // Mark as found
            } else {
                box.style.display = 'none'; // Hide the box
            }
        });

        // Show or hide the not found message based on search results
        const notFoundMessage = document.getElementById('not-found-message');
        if (!found) {
            notFoundMessage.style.display = 'flex'; // Show not found message
        } else {
            notFoundMessage.style.display = 'none'; // Hide not found message
        }
    }
</script>