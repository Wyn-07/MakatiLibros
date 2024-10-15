<div id="editProfileModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Profile
            </div>
            <span class="modal-close" onclick="closeEditProfileModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error" style="display: none">
            <div class="container-error-description" id="message"></div>
            <button type="button" class="button-error-close" onclick="closeErrorStatus()">&times;</button>
        </div>


        <form action="functions/update_profile.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">

            <input type="hidden" id="editLibrarianId" name="librarian_id" value="<?php echo htmlspecialchars($librarian_id); ?>">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-form-profile">
                        <div class="form-profile">
                            <img src="../librarian_images/<?php echo htmlspecialchars($image); ?>" class="image" id="imageProfilePreview">
                        </div>
                    </div>

                    <div class="row-center">
                        <div class="container-input-file-profile">
                            <input type="file" class="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewProfileImage(event)">
                        </div>
                    </div>


                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_firstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="profile_firstname" name="profile_firstname" class="input-text" oninput="capitalize(this)" value="<?php echo htmlspecialchars($firstname); ?>" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="profile_middlename">Middle Name</label>
                        <input type="text" id="profile_middlename" name="profile_middlename" class="input-text" oninput="capitalize(this)" value="<?php echo htmlspecialchars($middlename); ?>" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_lastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="profile_lastname" name="profile_lastname" class="input-text" oninput="capitalize(this)" value="<?php echo htmlspecialchars($lastname); ?>" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="profile_suffix">Suffix</label>
                        <input type="text" id="profile_suffix" name="profile_suffix" class="input-text" oninput="capitalize(this)" value="<?php echo htmlspecialchars($suffix); ?>" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_birthdate">Birthdate:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="date" id="profile_birthdate" name="profile_birthdate" class="input-text" onchange="calculateAge()" value="<?php echo htmlspecialchars($formattedBirthdate); ?>" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_age">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="profile_age" name="profile_age" class="input-text" autocomplete="off" value="<?php echo htmlspecialchars($age); ?>" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_gender">Gender</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="profile_gender" name="profile_gender" required>
                            <option value="" disabled selected> </option>
                            <option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="LGBTQ+" <?php echo $gender === 'LGBTQ+' ? 'selected' : ''; ?>>LGBTQ+</option>
                        </select>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="profile_contact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="profile_contact" name="profile_contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" value="<?php echo htmlspecialchars($contact); ?>" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="profile_address">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="profile_address" name="profile_address" class="input-text" autocomplete="off" oninput="capitalize(this)" onkeydown="disableSpace(event)" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>

                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Update</button>
                </div>
            </div>
        </form>



    </div>
</div>


<script>
    function openEditProfileModal() {
        document.getElementById('editProfileModal').classList.add('show');

    }


    function closeEditProfileModal() {
        document.getElementById('editProfileModal').classList.remove('show');

        location.reload();
    }

    function saveChanges() {
        closeEditProfileModal();
    }
</script>


<script>
    function previewProfileImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageHistoryPreview = document.getElementById('imageProfilePreview');
            imageHistoryPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }


    function validateForm(filenames) {
        var resultErrorContainer = document.getElementById("container-error");
        var message = document.getElementById("message");
        message.innerHTML = "";

        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var isValid = true;

        filenames.forEach(function(filename) {
            var fileInput = document.getElementById(filename);
            var filePath = fileInput.value;

            if (!filePath) {
                return;
            }

            if (!allowedExtensions.exec(filePath)) {
                isValid = false;
                resultErrorContainer.style.display = "flex";
                message.innerHTML = "Only PNG, JPG, and JPEG files are accepted.";
                message.style.display = "block";
                fileInput.style.border = '2px solid red';
                fileInput.value = '';
            } else {
                fileInput.style.border = '';
            }
        });

        if (isValid) {
            resultErrorContainer.style.display = "none";
            message.style.display = "none";
        }

        return isValid;
    }


    document.getElementById('form').onsubmit = function() {
            return validateForm(['profile_image']);
    };
</script>