<?php include '../connection.php'; ?>

<?php include 'functions/fetch_profile.php'; ?>

<?php
$patrons_id = $_SESSION['patrons_id']; // Get the current user's ID from the session

// Query to check for unread notifications for the user
$query = "SELECT * FROM notification WHERE borrow_id IN (SELECT borrow_id FROM borrow WHERE patrons_id = ?) AND seen = 'No'";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute([$patrons_id]);

// Check if there are any unread notifications
$unread_notifications = $stmt->fetchAll();
?>



<div class="row row-between-top">

    <div class="row-auto">
        <div class="container-round menu" id="menuButton">
            <img src="../images/menu-white.png" class="image">
        </div>
        <div class="container-round logo">
            <img src="../images/library-logo.png" class="image">
        </div>

        <div class="container-top-title">
            Makati City Hall Library
        </div>

    </div>

    <div class="row-auto">

        <a href="notification.php" class="profile-row notif-hover">

            <div class="icon-profile">
                <?php
                // If there are unread notifications, show the active notification image
                if (count($unread_notifications) > 0) {
                    echo '<img src="../images/notification-white-active.png" class="image" alt="Unread Notifications">';
                } else {
                    // Show the regular notification image if no unread notifications
                    echo '<img src="../images/notification-white.png" class="image" alt="No Unread Notifications">';
                }
                ?>
            </div>

            <div class="container-column">
                <div class="font-size-16">Notification</div>
            </div>

        </a>









        <!-- <a href="logout.php" class="link-logout">Log out</a> -->

        <a href="profile.php">
            <div class="container-round profile">
                <img src="../patron_images/<?php echo htmlspecialchars($image); ?>" class="image">
            </div>
        </a>

    </div>


</div>