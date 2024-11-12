<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Librarian Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>


        <div class="container-error" id="container-error-editpatron" style="display: none">
            <div class="container-error-description" id="message-editpatron"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditPatronsStatus()">&times;</button>
        </div>

        <form action="functions/update_librarian.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateEditForm(['edit_image'], 'editContact')">

            <input type="hidden" id="editID" name="librarians_id">
            
            <div class="container-form">

                <div class="container-input">

                    <div class="container-form-patron">
                        <div class="form-patron">
                            <img src="../images/no-image.png" alt="" class="image" id="imageEditPatronsPreview">
                        </div>
                    </div>
                    <div class="row-center">
                        <div class="container-input-file-patron">
                            <input type="file" class="file" name="edit_image" id="edit_image" accept="image/*" onchange="previewEditPatronsImage(event)">
                        </div>
                    </div>


                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editFirstname" name="edit_firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editMiddlename">Middle Name</label>
                        <input type="text" id="editMiddlename" name="edit_middlename" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editLastname" name="edit_lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editSuffix">Suffix</label>
                        <input type="text" id="editSuffix" name="edit_suffix" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editBirthdate">Birthdate:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="date" id="editBirthdate" name="edit_birthdate" max="2020-01-01" class="input-text" onchange="calculateEditAge()" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="editAge" name="edit_age" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editGender">Gender:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="editGender" name="edit_gender" required>
                            <option value="" disabled selected> </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="LGBTQ+">LGBTQ+</option>
                        </select>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editContact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editContact" name="edit_contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="editAddress">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editAddress" name="edit_address" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>
                

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="editEmail">Email:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="email" id="editEmail" name="edit_email" class="input-text" autocomplete="off" required>
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
    function openEditModal(element) {
        // Retrieve data attributes from the clicked element
        const librarianId = decodeURIComponent(element.getAttribute('data-librarian-id'));
        const firstname = decodeURIComponent(element.getAttribute('data-librarian-firstname'));
        const middlename = decodeURIComponent(element.getAttribute('data-librarian-middlename'));
        const lastname = decodeURIComponent(element.getAttribute('data-librarian-lastname'));
        const suffix = decodeURIComponent(element.getAttribute('data-librarian-suffix'));
        const birthdate = decodeURIComponent(element.getAttribute('data-librarian-birthdate'));
        const age = decodeURIComponent(element.getAttribute('data-librarian-age'));
        const gender = decodeURIComponent(element.getAttribute('data-librarian-gender'));
        const contact = decodeURIComponent(element.getAttribute('data-librarian-contact'));
        const address = decodeURIComponent(element.getAttribute('data-librarian-address'));
        const email = decodeURIComponent(element.getAttribute('data-librarian-email'));
        const image = decodeURIComponent(element.getAttribute('data-librarian-image'));


        // Show the modal
        document.getElementById('editModal').classList.add('show');

        // Populate the modal fields with the decoded values
        document.getElementById('editID').value = librarianId;
        document.getElementById('editFirstname').value = firstname;
        document.getElementById('editMiddlename').value = middlename;
        document.getElementById('editLastname').value = lastname;
        document.getElementById('editSuffix').value = suffix;
        document.getElementById('editBirthdate').value = birthdate;
        document.getElementById('editAge').value = age;
        document.getElementById('editGender').value = gender;
        document.getElementById('editContact').value = contact;
        document.getElementById('editAddress').value = address;
        document.getElementById('editEmail').value = email;


        // Set the image preview
        document.getElementById('imageEditPatronsPreview').src = '../librarian_images/' + image;

        // Clear the file input to allow a new selection
        document.getElementById('edit_image').value = '';



    }



    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>


<script>
    function previewEditPatronsImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageBookPreview = document.getElementById('imageEditPatronsPreview');
            imageBookPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<script src="js/input-validation-editpatrons.js"></script>
<script src="js/close-status.js"></script>