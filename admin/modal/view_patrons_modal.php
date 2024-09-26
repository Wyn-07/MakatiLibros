<div id="viewModal" class="modal">
    <div class="modal-content-big">
        <div class="row row-between">
            <div class="title-26px">
                View Patron Information
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>

        <input type="hidden" id="viewPatronId" name="patrons_id">

        <div class="container-form">
            <div class="container-input">
                <div class="container-form-profile">
                    <div class="form-profile">
                        <img src="../images/no-image.png" alt="" class="image">
                    </div>
                </div>

                <div class="container-input-49">
                    <label for="viewFirstname">First Name:</label>
                    <input type="text" id="viewFirstname" name="firstname" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewMiddlename">Middle Name</label>
                    <input type="text" id="viewMiddlename" name="middlename" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewLastname">Last Name:</label>
                    <input type="text" id="viewLastname" name="lastname" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewSuffix">Suffix</label>
                    <input type="text" id="viewSuffix" name="suffix" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewBirthdate">Birthdate:</label>
                    <input type="date" id="viewBirthdate" name="birthdate" class="input-text" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewAge">Age:</label>
                    <input type="number" id="viewAge" name="age" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewGender">Gender:</label>
                    <input type="text" id="viewGender" name="gender" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewContact">Contact:</label>
                    <input type="text" id="viewContact" name="contact" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-100">
                    <label for="viewAddress">Address:</label>
                    <input type="text" id="viewAddress" name="address" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-100">
                    <label for="viewInterest">Interest</label>
                    <textarea id="viewInterest" name="interest" class="input-text" disabled></textarea>
                </div>

                <div class="container-input-49">
                    <label for="viewEmail">Email:</label>
                    <input type="email" id="viewEmail" name="email" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="viewPassword">Password:</label>
                    <input type="password" id="viewPassword" name="password" class="input-text" autocomplete="off" disabled>
                </div>
            </div>

            <div class="row row-right">
                <button type="button" class="button-submit" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openViewModal(patronId, firstname, middlename, lastname, suffix, birthdate, age, gender, contact, address, interest, email, password) {
        document.getElementById('viewModal').classList.add('show');
        document.getElementById('viewPatronId').value = patronId;
        document.getElementById('viewFirstname').value = firstname;
        document.getElementById('viewMiddlename').value = middlename;
        document.getElementById('viewLastname').value = lastname;
        document.getElementById('viewSuffix').value = suffix;
        document.getElementById('viewBirthdate').value = birthdate;
        document.getElementById('viewAge').value = age;
        document.getElementById('viewGender').value = gender;
        document.getElementById('viewContact').value = contact;
        document.getElementById('viewAddress').value = address;
        document.getElementById('viewInterest').value = interest;
        document.getElementById('viewEmail').value = email;
        document.getElementById('viewPassword').value = password;
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');
    }
</script>
