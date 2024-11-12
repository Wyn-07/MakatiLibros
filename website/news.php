<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

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
            <?php include 'navbar.php'; ?>
        </div>

        <div id="overlay" class="overlay"></div>

        <div class="row-body-padding-0">

            <div class="container-sidebar" id="sidebar">
                <?php include 'sidebar.php'; ?>
            </div>


            <div class="container-content">


                <div class="container-news">
                    <div class="transparent-news">
                        <div class="news-title-white">
                            News
                        </div>
                        <div class="news-subtitle-white">
                            Want to stay updated? We'd love to keep you informed.
                        </div>
                        <div class="news-subtitle-white">
                            Here's how you can stay up-to-date with the latest news.
                        </div>
                    </div>
                </div>


                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>


                <div class="news-contents">

                    <div class="row row-right">

                        <div class="container-search row">
                            <input type="text" id="search" class="search" placeholder="Search..." onkeyup="searchTable()">

                            <div class="container-search-image">
                                <div class="search-image">
                                    <img src="../images/search-black.png" class="image">
                                </div>
                            </div>
                        </div>



                    </div>

                    <div id="not-found-message" class="container-unavailable" style="display: none;">
                        <div class="unavailable-image">
                            <img src="../images/no-books.png" class="image">
                        </div>
                        <div class="unavailable-text">Not Found</div>
                    </div>


                    <?php foreach ($news as $item) : ?>

                        <div class="news-box-container">
                            <div class="news-image">
                                <img src="../news_images/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="image">
                            </div>

                            <div class="news-box-right">
                                <div class="news-title">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </div>

                                <div class="news-date">
                                    <?php echo htmlspecialchars($item['date']); ?>
                                </div>

                                <div class="news-description fade">
                                    <?php echo htmlspecialchars($item['description']); ?>

                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>






                </div>





            </div>



        </div>


        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>

    </div>

</body>



</html>


<script src="js/banner.js"></script>
<script src="js/sidebar.js"></script>
<script src="js/loading-animation.js"></script>

<script>
    document.querySelectorAll('.news-box-container').forEach(container => {
        container.addEventListener('click', function() {
            container.classList.toggle('expanded');
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