<div id="addModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Add Patron Information
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>


        <div class="container-error" id="container-error-addpatron" style="display: none">
            <div class="container-error-description" id="message-addpatron"></div>
            <button type="button" class="button-error-close" onclick="closeErrorAddPatronsStatus()">&times;</button>
        </div>

        <form action="functions/add_patrons.php" method="POST" enctype="multipart/form-data" id="addForm" onsubmit="return validateAddForm(['add_image'], 'addContact')">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-form-patron">
                        <div class="form-patron">
                            <img src="../patron_images/default_image.png" alt="" class="image" id="imageAddPatronsPreview">
                        </div>
                    </div>
                    <div class="row-center">
                        <div class="container-input-file-patron">
                            <input type="file" class="file" name="add_image" id="add_image" accept="image/*" onchange="previewAddPatronsImage(event)">
                        </div>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addFirstname" name="firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addMiddlename">Middle Name</label>
                        <input type="text" id="addMiddlename" name="middlename" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addLastname" name="lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addSuffix">Suffix</label>
                        <input type="text" id="addSuffix" name="suffix" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addBirthdate">Birthdate:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="date" id="addBirthdate" name="birthdate" max="2020-01-01" class="input-text" onchange="calculateAddAge()" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="addAge" name="age" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGender">Gender:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="addGender" name="gender" required>
                            <option value="" disabled selected> </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="LGBTQ+">LGBTQ+</option>
                        </select>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addContact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addContact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="addAddress">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addAddress" name="address" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCompanyName">Company Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addCompanyName" name="company_name" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCompanyContact">Company Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addCompanyContact" name="company_contact" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="addCompanyAddress">Company Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addCompanyAddress" name="company_address" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addEmail">Email:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="email" id="addEmail" name="email" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addPassword">Password:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addPassword" name="password" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-100"></div>

                    <div class="title-26px container-input-100">
                        Guarantor Information
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGuarantorFirstname">Guarantor First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorFirstname" name="guarantor_firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addGuarantorMiddlename">Guarantor Middle Name</label>
                        <input type="text" id="addGuarantorMiddlename" name="guarantor_middlename" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGuarantorLastname">Guarantor Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorLastname" name="guarantor_lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addGuarantorSuffix">Guarantor Suffix</label>
                        <input type="text" id="addGuarantorSuffix" name="guarantor_suffix" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGuarantorContact">Guarantor Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorContact" name="guarantor_contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGuarantorAddress">Guarantor Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorAddress" name="guarantor_address" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGuarantorCompanyName">GuarantorCompany Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorCompanyName" name="guarantor_company_name" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCompanyContact">Guarantor Company Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorCompanyContact" name="guarantor_company_contact" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="addGuarantorCompanyAddress">Guarantor Company Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addGuarantorCompanyAddress" name="guarantor_company_address" class="input-text" autocomplete="off">
                    </div>



                </div>

                <div class="row row-right">
                    <button type="submit" name="submit" class="button-submit">Submit</button>
                </div>
            </div>

        </form>

    </div>
</div>



<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('show');
    }


    function generatePassword(length) {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        let password = "";
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        return password;
    }

    function setGeneratedPassword() {
        const passwordField = document.getElementById('addPassword');
        if (passwordField) {
            passwordField.value = generatePassword(12); // Change the number to set the length of the password
        }
    }

    // Call the function to set the generated password when the modal or form is shown
    document.addEventListener('DOMContentLoaded', function() {
        setGeneratedPassword();
    });
</script>



<script>
    function previewAddPatronsImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imageBookPreview = document.getElementById('imageAddPatronsPreview');
            imageBookPreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script src="js/input-validation-addpatrons.js"></script>
<script src="js/close-status.js"></script>