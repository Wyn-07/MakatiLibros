<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

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

        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>

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
                        About
                    </div>
                </div>


                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Hide the loading overlay after the content is fully loaded
                        document.getElementById("loading-overlay").style.display = "none";
                    });

                    // Show the loading overlay when the page is being reloaded or navigated
                    window.addEventListener("beforeunload", function() {
                        document.getElementById("loading-overlay").style.display = "flex";
                    });
                </script>


                <form action="functions/update_about.php" method="POST" enctype="multipart/form-data" class="container-white-about"
                    onsubmit="return validateAboutForm(['mission_image_1'], ['mission_image_2'], ['mission_image_3'], ['vision_image_1'], ['vision_image_2'], ['vision_image_3'])">

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


                    <div class="container-error" id="container-error-about" style="display: none">
                        <div class="container-error-description" id="message-about"></div>
                        <button type="button" class="button-error-close" onclick="closeErrorAboutStatus()">&times;</button>
                    </div>

                    <div style="display: none; align-items: center;">
                        <div id="brTooltip" class="container-info">
                            <div class="container-row">

                                Please ignore the &lt;br&gt; tags you see; they were inserted for creating new lines on the website.
                            </div>
                        </div>
                    </div>

                    <div class="row row-between">

                        <div class="nav-page">
                            <button id="nav-mission" class="nav-button" type="button" onclick="changePage(1)">Mission & Vision</button>
                            <button id="nav-history" class="nav-button" type="button" onclick="changePage(2)">History</button>
                            <button id="nav-officials" class="nav-button" type="button" onclick="changePage(3)">Officials</button>
                        </div>

                        <button type="submit" name="submit" id="submit" class="button-submit">Update</button>
                        <button onclick="openAddModal()" class="button-add" type="button" style="display: none">&#43; New</button>

                    </div>


                    <div class="row row-center about-80vh" id="mission" style="display:flex">

                        <div class="about-mv-contents">
                            <div class="row row-right">
                                <div class="about-mv-image-1" id="mission-image-1">
                                    <img src="../about_images/<?php echo $mission_image_1; ?>" alt="" class="image-cover" id="imageMission1Preview" onclick="triggerFileInput('mission_image_1')">
                                </div>
                                <div class="about-mv-column">
                                    <div class="about-mv-image-2">
                                        <img src="../about_images/<?php echo $mission_image_2; ?>" alt="" class="image-cover" id="imageMission2Preview" onclick="triggerFileInput('mission_image_2')">
                                    </div>
                                    <div class="about-mv-image-3">
                                        <img src="../about_images/<?php echo $mission_image_3; ?>" alt="" class="image-cover" id="imageMission3Preview" onclick="triggerFileInput('mission_image_3')">
                                    </div>
                                </div>
                            </div>

                            <input type="file" class="file" name="mission_image_1" id="mission_image_1" accept="image/*" onchange="previewMission1Image(event)" style="display: none">
                            <input type="file" class="file" name="mission_image_2" id="mission_image_2" accept="image/*" onchange="previewMission2Image(event)" style="display: none">
                            <input type="file" class="file" name="mission_image_3" id="mission_image_3" accept="image/*" onchange="previewMission3Image(event)" style="display: none">

                            <script>
                                function triggerFileInput(inputId) {
                                    document.getElementById(inputId).click();
                                }

                                function previewMission1Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageMission1Preview = document.getElementById('imageMission1Preview');
                                        imageMission1Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }


                                function previewMission2Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageMission2Preview = document.getElementById('imageMission2Preview');
                                        imageMission2Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }


                                function previewMission3Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageMission3Preview = document.getElementById('imageMission3Preview');
                                        imageMission3Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }
                            </script>


                        </div>

                        <div class="about-mv-contents" id="mission">
                            <div class="about-title">
                                Our Mission
                            </div>
                            <div class="about-description">
                                <textarea name="mission" id="mission_desc" class="textarea-about"><?php echo $mission; ?></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="row row-center about-80vh" id="vision" style="display:flex">
                        <div class="about-mv-contents">
                            <div class="about-title">
                                Our Vision
                            </div>
                            <div class="about-description">
                                <textarea name="vision" id="vision_desc" class="textarea-about"><?php echo $vision; ?></textarea>
                            </div>
                        </div>

                        <div class="about-mv-contents">
                            <div class="row row-right">
                                <div class="about-mv-image-1">
                                    <img src="../about_images/<?php echo $vision_image_1; ?>" alt="" class="image-cover" id="imageVision1Preview" onclick="triggerFileInput('vision_image_1')">
                                </div>
                                <div class="about-mv-column">
                                    <div class="about-mv-image-2">
                                        <img src="../about_images/<?php echo $vision_image_2; ?>" alt="" class="image-cover" id="imageVision2Preview" onclick="triggerFileInput('vision_image_2')">
                                    </div>
                                    <div class="about-mv-image-3">
                                        <img src="../about_images/<?php echo $vision_image_3; ?>" alt="" class="image-cover" id="imageVision3Preview" onclick="triggerFileInput('vision_image_3')">
                                    </div>
                                </div>
                            </div>

                            <input type="file" class="file" name="vision_image_1" id="vision_image_1" accept="image/*" onchange="previewVision1Image(event)" style="display: none">
                            <input type="file" class="file" name="vision_image_2" id="vision_image_2" accept="image/*" onchange="previewVision2Image(event)" style="display: none">
                            <input type="file" class="file" name="vision_image_3" id="vision_image_3" accept="image/*" onchange="previewVision3Image(event)" style="display: none">

                            <script>
                                function triggerFileInput(inputId) {
                                    document.getElementById(inputId).click();
                                }

                                function previewVision1Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageVision1Preview = document.getElementById('imageVision1Preview');
                                        imageVision1Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }


                                function previewVision2Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageVision2Preview = document.getElementById('imageVision2Preview');
                                        imageVision2Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }


                                function previewVision3Image(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imageVision3Preview = document.getElementById('imageVision3Preview');
                                        imageVision3Preview.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }
                            </script>
                        </div>

                    </div>


                    <div class="about-history-contents" id="history" style="display:none">
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
                                            <textarea name="history" id="history_desc" class="textarea-history"><?php echo htmlspecialchars($history); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="container-scroll-right">
                                    </div>

                                </div>

                            </div>


                        </div>


                    </div>

                </form>


                <div class="about-officials-contents" id="officials" style="display:none">
                    <div class="about-title">
                        Officials of the Makati City Hall Library
                    </div>

                    <div class="row-contents-center">

                        <?php foreach ($officials as $official) : ?>
                            <form action="functions/update_official.php" method="POST" enctype="multipart/form-data" id="form_<?php echo htmlspecialchars($official['officials_id']); ?>" onsubmit="return validateFormBeforeSubmit(this);">
                           
                            <input type="hidden" name="oldImageName" value="<?php echo htmlspecialchars($official['image']); ?>">
                            <input type="hidden" name="oldName" value="<?php echo htmlspecialchars($official['name']); ?>">
                            <input type="hidden" name="oldTitle" value="<?php echo htmlspecialchars($official['title']); ?>">

                                <div class="container-officials">
                                    <div class="container-officials-image" id="container-official-image_<?php echo htmlspecialchars($official['officials_id']); ?>">
                                        <img src="../official_images/<?php echo htmlspecialchars($official['image']); ?>"
                                            alt="Official Image"
                                            class="image"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            id="imageOfficialPreview_<?php echo htmlspecialchars($official['officials_id']); ?>"
                                            onclick="triggerFileInput('official_image_<?php echo htmlspecialchars($official['officials_id']); ?>')">
                                    </div>


                                    <input type="file" class="file"
                                        name="official_image"
                                        id="official_image_<?php echo htmlspecialchars($official['officials_id']); ?>"
                                        accept="image/*"
                                        style="display: none">

                                    <div class="container-officials-description">
                                        <input type="hidden" name="official_id" value="<?php echo htmlspecialchars($official['officials_id']); ?>">
                                        <input type="text" name="name" class="input-text officials-name" value="<?php echo htmlspecialchars($official['name']); ?>">
                                        <input type="text" name="title" class="input-text officials-title" value="<?php echo htmlspecialchars($official['title']); ?>">
                                        <div class="row row-between">
                                            <button type="button" class="button-delete-officials" onclick="openDeleteModal('<?php echo htmlspecialchars($official['officials_id']); ?>')">Delete</button>
                                            <button type="submit" name="save" id="save" class="button-save">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php endforeach; ?>



                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const fileInputs = document.querySelectorAll('input[type="file"]');
                                const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i; // Allowed file types

                                fileInputs.forEach(input => {
                                    input.addEventListener('change', function(event) {
                                        const officialId = this.id.split('_').pop(); // Get official ID from the input ID
                                        const imageOfficialContainer = document.getElementById('container-official-image_' + officialId); // Get the container instead of image preview
                                        const file = event.target.files[0]; // Get selected file

                                        if (file) {
                                            const filePath = event.target.value;

                                            const reader = new FileReader();
                                            reader.onload = function() {
                                                document.getElementById('imageOfficialPreview_' + officialId).src = reader.result; // Update image source
                                            };
                                            reader.readAsDataURL(file);

                                            // Validate file type
                                            if (!allowedExtensions.exec(filePath)) {
                                                imageOfficialContainer.style.border = '2px solid red';

                                            } else {
                                                resultErrorContainer.style.display = "none"; // Hide error container if all inputs are valid
                                                message.style.display = "none"; // Hide message
                                                imageOfficialContainer.style.border = '';
                                            }
                                        }
                                    });
                                });
                            });


                            // Prevent form submission if the file is not valid
                            function validateFormBeforeSubmit(form) {
                                var resultErrorContainer = document.getElementById("container-error-about");
                                var message = document.getElementById("message-about");
                                message.innerHTML = ""; // Clear previous messages

                                const fileInputs = form.querySelectorAll('input[type="file"]'); // Select file inputs only from the current form
                                const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i; // Allowed file types
                                let isValid = true;

                                // Reset all container borders for the current form
                                fileInputs.forEach(input => {
                                    const officialId = input.id.split('_').pop(); // Get official ID from the input ID
                                    const imageOfficialContainer = document.getElementById('container-official-image_' + officialId);
                                    imageOfficialContainer.style.border = ''; // Reset the border initially
                                });

                                // Validate file inputs for the current form
                                fileInputs.forEach(input => {
                                    const filePath = input.value;

                                    // If the file input has a file selected and it's not valid, prevent form submission
                                    if (filePath && !allowedExtensions.exec(filePath)) {
                                        isValid = false; // Mark as invalid
                                        resultErrorContainer.style.display = "flex"; // Show error container
                                        message.innerHTML = "Only PNG, JPG, and JPEG files are accepted."; // Set error message
                                        message.style.display = "block"; // Show message

                                        const officialId = input.id.split('_').pop(); // Get official ID from the input ID
                                        const imageOfficialContainer = document.getElementById('container-official-image_' + officialId);
                                        imageOfficialContainer.style.border = '2px solid red'; // Set red border for invalid files
                                    } else {
                                        // If valid, reset the border for this input
                                        const officialId = input.id.split('_').pop(); // Get official ID from the input ID
                                        const imageOfficialContainer = document.getElementById('container-official-image_' + officialId);
                                        imageOfficialContainer.style.border = ''; // Reset border for valid files
                                    }
                                });

                                return isValid; // Return true if all files are valid, otherwise prevent form submission
                            }


                            function triggerFileInput(inputId) {
                                document.getElementById(inputId).click();
                            }
                        </script>






                    </div>


                </div>


            </div>

        </div>


    </div>



    <?php include 'modal/add_official_modal.php'; ?>
    <?php include 'modal/delete_official_modal.php'; ?>


</body>

</html>



<script src="js/close-status.js"></script>



<script>
    function changePage(page) {
        // Hide all sections initially
        document.getElementById('mission').style.display = 'none';
        document.getElementById('vision').style.display = 'none';
        document.getElementById('history').style.display = 'none';
        document.getElementById('officials').style.display = 'none';

        // Select the buttons
        const submitButton = document.getElementById('submit');
        const addButton = document.querySelector('.button-add');

        // Remove 'active' class from all buttons
        const allButtons = document.querySelectorAll('.nav-button');
        allButtons.forEach(button => button.classList.remove('active'));

        // Page 1: Mission and Vision
        if (page === 1) {
            document.getElementById('mission').style.display = 'flex';
            document.getElementById('vision').style.display = 'flex';
            submitButton.style.display = 'flex';
            addButton.style.display = 'none';

            // Set active button
            document.getElementById('nav-mission').classList.add('active');
        }
        // Page 2: History
        else if (page === 2) {
            document.getElementById('history').style.display = 'flex';
            submitButton.style.display = 'flex';
            addButton.style.display = 'none';

            // Set active button
            document.getElementById('nav-history').classList.add('active');
        }
        // Page 3: Officials
        else if (page === 3) {
            document.getElementById('officials').style.display = 'flex';
            submitButton.style.display = 'none';
            addButton.style.display = 'flex';

            // Set active button
            document.getElementById('nav-officials').classList.add('active');
        }
    }


    changePage(1);
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        function adjustHeight() {
            var containerScroll = document.querySelector('.container-scroll');
            var aboutHistoryContents = document.querySelector('.about-history-contents');

            if (containerScroll && aboutHistoryContents) {
                var containerScrollHeight = containerScroll.offsetHeight;
                var extraSpace = 800;
                aboutHistoryContents.style.height = (containerScrollHeight + extraSpace) + 'px';
            }
        }

        adjustHeight();

        window.addEventListener('resize', adjustHeight);
    });
</script>



<script>
    function validateAboutForm(missionImage1, missionImage2, missionImage3, visionImage1, visionImage2, visionImage3) {
        var resultErrorContainer = document.getElementById("container-error-about");
        var message = document.getElementById("message-about");
        message.innerHTML = "";

        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var isValid = true;

        missionImage1.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            // Get the corresponding image preview element
            var imagePreview = document.getElementById('imageMission1Preview');

            if (!filePath) {
                return; // Skip validation if no file is selected
            }

            // Check file extension
            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red'; // Highlight the image with red border
            } else {
                imagePreview.style.border = ''; // Reset border if valid
            }
        });

        missionImage2.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            // Get the corresponding image preview element
            var imagePreview = document.getElementById('imageMission2Preview');

            if (!filePath) {
                return; // Skip validation if no file is selected
            }

            // Check file extension
            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red'; // Highlight the image with red border
            } else {
                imagePreview.style.border = ''; // Reset border if valid
            }
        });

        missionImage3.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            // Get the corresponding image preview element
            var imagePreview = document.getElementById('imageMission3Preview');

            if (!filePath) {
                return; // Skip validation if no file is selected
            }

            // Check file extension
            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red'; // Highlight the image with red border
            } else {
                imagePreview.style.border = ''; // Reset border if valid
            }
        });


        visionImage1.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            var imagePreview = document.getElementById('imageVision1Preview');

            if (!filePath) {
                return;
            }

            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red';
            } else {
                imagePreview.style.border = '';
            }
        });


        visionImage2.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            var imagePreview = document.getElementById('imageVision2Preview');

            if (!filePath) {
                return;
            }

            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red';
            } else {
                imagePreview.style.border = '';
            }
        });

        visionImage3.forEach(function(inputId) {
            var fileInput = document.getElementById(inputId);
            var filePath = fileInput.value;

            var imagePreview = document.getElementById('imageVision3Preview');

            if (!filePath) {
                return;
            }

            // Check file extension
            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                imagePreview.style.border = '2px solid red'; // Highlight the image with red border
            } else {
                imagePreview.style.border = ''; // Reset border if valid
            }
        });




        // Hide error messages if everything is valid
        if (isValid) {
            resultErrorContainer.style.display = "none";
            message.style.display = "none";
        }

        return isValid; // Return true if all inputs are valid
    }
</script>


<script>
    const textAreas = [
        document.getElementById("history_desc"),
        document.getElementById("mission_desc"),
        document.getElementById("vision_desc")
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