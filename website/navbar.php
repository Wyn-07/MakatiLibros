
<?php include '../connection.php'; ?>
<?php include 'functions/fetch_profile.php'; ?>


<div class="row row-between-top">

    <div class="row-auto">
        <div class="container-round menu" id="menuButton">
            <img src="../images/menu-white.png" class="image">
        </div>
        <div class="container-round logo">
            <img src="../images/makati-logo.png" class="image">
        </div>
        Makati City Hall Library
    </div>

    <div class="row-auto">

        <a href="logout.php" class="link-logout">Log out</a>

        <a href="profile.php">
            <div class="container-round profile">
                <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image">
            </div>
        </a>

    </div>


</div>