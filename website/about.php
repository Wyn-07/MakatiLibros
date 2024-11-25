<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php

session_start();

include '../connection.php';

include 'functions/fetch_about.php';
$about = getAbout($pdo);

if ($about) {
    $vision = $about['vision'];
    $vision_image_1 = $about['vision_image_1'];
    $vision_image_2 = $about['vision_image_2'];
    $vision_image_3 = $about['vision_image_3'];
    $mission = $about['mission'];
    $mission_image_1 = $about['mission_image_1'];
    $mission_image_2 = $about['mission_image_2'];
    $mission_image_3 = $about['mission_image_3'];
    $history = $about['history'];
}

include 'functions/fetch_officials.php';
$officials = getOfficials($pdo);

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

                <div class="about-banner">
                    <div class="transparent-about">
                        <div class="about-title-white">
                            About Us
                        </div>
                        <div class="about-subtitle-white">
                            Discover more about our organization and the people behind it.
                        </div>
                        <div class="about-subtitle-white">
                            Learn about our mission, vision, history, and the officials who lead us.
                        </div>
                    </div>
                </div>


                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>


                <div class="body-navbar">
                    <div class="body-navbar-contents" onclick="scrollToSection('mission')">Mission</div>
                    <div class="body-navbar-contents">|</div>
                    <div class="body-navbar-contents" onclick="scrollToSection('vision')">Vision</div>
                    <div class="body-navbar-contents">|</div>
                    <div class="body-navbar-contents" onclick="scrollToSection('history')">History</div>
                    <div class="body-navbar-contents">|</div>
                    <div class="body-navbar-contents" onclick="scrollToSection('officials')">Officials</div>
                </div>

                <div class="row row-center about-80vh">
                    <div class="about-mv-contents">
                        <div class="row row-right">
                            <div class="about-mv-image-1">
                                <img src="../about_images/<?php echo $mission_image_1; ?>" alt="" class="image-cover">
                            </div>
                            <div class="about-mv-column">
                                <div class="about-mv-image-2">
                                    <img src="../about_images/<?php echo $mission_image_2; ?>" alt="" class="image-cover">
                                </div>
                                <div class="about-mv-image-3">
                                    <img src="../about_images/<?php echo $mission_image_3; ?>" alt="" class="image-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="about-mv-contents" id="mission">
                        <div class="about-title">
                            Our Mission
                        </div>
                        <div class="about-description">
                            <?php echo $mission; ?>
                        </div>
                    </div>
                </div>


                <div class="row row-center about-80vh" id="vision">
                    <div class="about-mv-contents">
                        <div class="about-title">
                            Our Vision
                        </div>
                        <div class="about-description">
                            <?php echo $vision; ?>
                        </div>
                    </div>

                    <div class="about-mv-contents">
                        <div class="row row-right">
                            <div class="about-mv-image-1">
                                <img src="../about_images/<?php echo $vision_image_1; ?>" alt="" class="image-cover">
                            </div>
                            <div class="about-mv-column">
                                <div class="about-mv-image-2">
                                    <img src="../about_images/<?php echo $vision_image_2; ?>" alt="" class="image-cover">
                                </div>
                                <div class="about-mv-image-3">
                                    <img src="../about_images/<?php echo $vision_image_3; ?>" alt="" class="image-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="about-history-contents" id="history">
                    <div class="about-history-image">
                        <img src="../images/city-hall.jpg" alt="" class="image-cover">
                        <div class="history-image-overlay"></div>

                        <div class="container-scroll">

                            <div class="container-scroll-body">

                                <div class="container-scroll-left">
                                </div>
                                <div class="container-history-description">
                                    <div class="history-title">
                                        History of Makati City Hall Library
                                    </div>
                                    <div class="history-description">
                                        <?php echo $history; ?>
                                    </div>
                                </div>
                                <div class="container-scroll-right">
                                </div>

                            </div>

                        </div>


                    </div>


                </div>


                <div class="about-officials-contents" id="officials">
                    <div class="about-title">
                        Officials of the Makati City Hall Library
                    </div>

                    <div class="row-contents-center">

                        <?php foreach ($officials as $official) : ?>
                            <div class="container-officials">

                                <div class="container-officials-image">
                                    <img src="../official_images/<?php echo htmlspecialchars($official['image']); ?>"
                                        alt="Official Image"
                                        class="image"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>


                                <div class="container-officials-description">
                                    <div class="officials-name">
                                        <?php echo htmlspecialchars($official['name']); ?>
                                    </div>
                                    <div class="officials-title">
                                        <?php echo htmlspecialchars($official['title']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>


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
    document.addEventListener('DOMContentLoaded', function() {
        function adjustHeight() {
            var containerScroll = document.querySelector('.container-scroll');
            var aboutHistoryContents = document.querySelector('.about-history-contents');

            if (containerScroll && aboutHistoryContents) {
                var containerScrollHeight = containerScroll.offsetHeight;
                var extraSpace = 170; // Adjust this value to add more space below
                aboutHistoryContents.style.height = (containerScrollHeight + extraSpace) + 'px';
            }
        }

        adjustHeight();

        window.addEventListener('resize', adjustHeight);
    });
</script>


<script>
    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);

        if (section) {
            const sectionPosition = section.getBoundingClientRect().top + window.scrollY;

            const offset = 100;

            window.scrollTo({
                top: sectionPosition - offset,
                behavior: 'smooth'
            });
        }
    }
</script>