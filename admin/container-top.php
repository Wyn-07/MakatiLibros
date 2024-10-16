<?php include 'functions/fetch_profile.php'; ?>



<div class="row row-between">

    <div class="row-auto">
        <div class="container-round logo">
            <img src="../images/makati-logo.png" class="image">
        </div>
        <div class="font-white">
            MakatiLibros
        </div>
    </div>


    <div class="row-auto container-profile" onclick="openEditProfileModal()">
        <div class="container-round profile">
            <img src="../librarian_images/<?php echo htmlspecialchars($image); ?>" class="image">
        </div>
        <div class="font-white">
            <?php echo htmlspecialchars($lastname); ?>
        </div>
    </div>

</div>




<?php include 'modal/edit_profile_modal.php'; ?>
<script src="js/input-validation-profile.js"></script>