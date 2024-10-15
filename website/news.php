<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">
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


                <div class="news-contents">

                    <div class="row row-right">

                        <div class="container-search row">
                            <input type="text" id="search" class="search" placeholder="">

                            <div class="container-search-image">
                                <div class="search-image">
                                    <img src="../images/search-black.png" class="image">
                                </div>
                            </div>
                        </div>

                    </div>

                    <?php foreach ($news as $item) : ?>
                        <div class="news-box-container">
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