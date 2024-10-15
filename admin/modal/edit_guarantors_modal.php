<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Guarantor Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-editguarantor" style="display: none">
            <div class="container-error-description" id="message-editguarantor"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditGuarantorStatus()">&times;</button>
        </div>

        <form action="functions/update_guarantors.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateEditForm('editContact')">
            <input type="hidden" id="editGuarantorId" name="guarantor_id">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editFirstname" name="firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editMiddlename">Middle Name</label>
                        <input type="text" id="editMiddlename" name="middlename" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editLastname" name="lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editSuffix">Suffix</label>
                        <input type="text" id="editSuffix" name="suffix" class="input-text" oninput="capitalize(this)" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editContact">Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editContact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editAddress">Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editAddress" name="address" class="input-text" oninput="capitalize(this)" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editCompanyName">Company Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editCompanyName" name="company_name" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editCompanyContact">Company Contact:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editCompanyContact" name="company_contact" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="editCompanyAddress">Company Address:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editCompanyAddress" name="company_address" class="input-text" autocomplete="off">
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
    function openEditModal(guarantor) {
    document.getElementById('editModal').classList.add('show');
    document.getElementById('editGuarantorId').value = guarantor.id;
    document.getElementById('editFirstname').value = guarantor.firstname;
    document.getElementById('editMiddlename').value = guarantor.middlename || '';
    document.getElementById('editLastname').value = guarantor.lastname;
    document.getElementById('editSuffix').value = guarantor.suffix || '';
    document.getElementById('editContact').value = guarantor.contact;
    document.getElementById('editAddress').value = guarantor.address;
    document.getElementById('editCompanyName').value = guarantor.company_name || '';
    document.getElementById('editCompanyContact').value = guarantor.company_contact || '';
    document.getElementById('editCompanyAddress').value = guarantor.company_address || '';
}


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>



<script src="js/input-validation-editguarantors.js"></script>
<script src="js/close-status.js"></script>