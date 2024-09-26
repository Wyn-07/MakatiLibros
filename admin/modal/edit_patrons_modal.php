<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Patron Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_patrons.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editPatronId" name="patrons_id">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-form-profile">
                        <div class="form-profile">
                            <img src="../images/no-image.png" alt="" class="image">
                        </div>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editFirstname" name="firstname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editMiddlename">Middle Name</label>
                        <input type="text" id="editMiddlename" name="middlename" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editLastname" name="lastname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editSuffix">Suffix</label>
                        <input type="text" id="editSuffix" name="suffix" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editBirthdate">Birthdate:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="date" id="editBirthdate" name="birthdate" class="input-text" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="editAge" name="age" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editGender">Gender:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editGender" name="gender" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editContact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editContact" name="contact" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="editAddress">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editAddress" name="address" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <label for="editInterest">Interest</label>
                        <textarea id="editInterest" name="interest" class="input-text"></textarea>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editEmail">Email:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="email" id="editEmail" name="email" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editPassword">Password:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="password" id="editPassword" name="password" class="input-text" autocomplete="off" required>
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
    function openEditModal(patronId, firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, interest, email, password) {
        document.getElementById('editModal').classList.add('show');
        document.getElementById('editPatronId').value = patronId;
        document.getElementById('editFirstname').value = firstname;
        document.getElementById('editMiddlename').value = middlename;
        document.getElementById('editLastname').value = lastname;
        document.getElementById('editSuffix').value = suffix;
        document.getElementById('editBirthdate').value = birthdate;
        document.getElementById('editAge').value = age;
        document.getElementById('editGender').value = gender;
        document.getElementById('editContact').value = contact;
        document.getElementById('editAddress').value = address;
        document.getElementById('editInterest').value = interest;
        document.getElementById('editEmail').value = email;
        document.getElementById('editPassword').value = password;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>