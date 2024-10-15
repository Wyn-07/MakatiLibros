<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

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
<!-- 
            <?php include 'container-top.php'; ?> -->

        </div>


        <div class="container-content">

            <div class="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="body">

                <div class="row">
                    <div class="title-26px">
                        Contact
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



                    <?php foreach ($contactData as $item) : ?>
                        <div class="contact-white-container" onclick="openEditModal(
                                        '<?php echo htmlspecialchars($item['contact_id']); ?>', 
                                        '<?php echo htmlspecialchars($item['title']); ?>', 
                                        '<?php echo htmlspecialchars($item['description']); ?>', 
                                        '<?php echo htmlspecialchars($item['contact']); ?>', 
                                        '<?php echo htmlspecialchars($item['image']); ?>'
                                    )">
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













                    <?php include 'modal/edit_contact_modal.php'; ?>




                </div>





            </div>

        </div>


    </div>



    <?php include 'modal/add_news_modal.php'; ?>



</body>

</html>



<script src="js/close-status.js"></script>





