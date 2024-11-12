<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php

session_start();

include '../connection.php';


include 'functions/fetch_contact.php';
$contactData = getContact($pdo);

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

                <div class="container-contact">
                    <div class="transparent-contact">
                        <div class="contact-title-white">
                            Contact Us
                        </div>
                        <div class="contact-subtitle-white">
                            Want to get in touch? We'd love to hear from you.
                        </div>
                        <div class="contact-subtitle-white">
                            Here how you can reach us.
                        </div>
                    </div>
                </div>


                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>



                <div class="contact-contents">

                    <div class="row-contents-center">

                        <?php foreach ($contactData as $item) : ?>
                            <div class="contact-white-container">
                                <div class="contact-icons">
                                    <img src="../contact_images/<?php echo htmlspecialchars($item['image']); ?>" class="image">
                                </div>
                                <div class="contact-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                <div class="contact-description">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </div>
                                <div class="contact-info"><?php echo htmlspecialchars($item['contact']); ?></div>
                            </div>
                        <?php endforeach; ?>


                    </div>
                </div>



            </div>



        </div>






    </div>
</body>



</html>


<script src="js/banner.js"></script>
<script src="js/sidebar.js"></script>
<script src="js/loading-animation.js"></script>