<div id="addModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Add Guarantor Information
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_guarantors.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="addGuarantorId" name="guarantor_id">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addFirstname" name="firstname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addMiddlename">Middle Name</label>
                        <input type="text" id="addMiddlename" name="middlename" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addLastname" name="lastname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="addSuffix">Suffix</label>
                        <input type="text" id="addSuffix" name="suffix" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addContact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addContact" name="contact" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addAddress">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addAddress" name="address" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCompanyName">Company Name:</label>
                        </div>
                        <input type="text" id="addCompanyName" name="company_name" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCompanyContact">Company Contact:</label>
                        </div>
                        <input type="text" id="addCompanyContact" name="company_contact" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="addCompanyAddress">Company Address:</label>
                        </div>
                        <input type="text" id="addCompanyAddress" name="company_address" class="input-text" autocomplete="off">
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
