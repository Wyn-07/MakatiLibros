<div id="viewModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                View Patron Information
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>

        <div class="container-error" id="container-error-editpatron" style="display: none">
            <div class="container-error-description" id="message-editpatron"></div>
            <button type="button" class="button-error-close" onclick="closeErrorEditPatronsStatus()">&times;</button>
        </div>

        <input type="hidden" id="editPatronId" name="patrons_id">
        <input type="hidden" id="editGuarantorId" name="guarantors_id">

        <div class="container-form">

            <div class="container-input">

                <div class="container-input-49">
                    <div class="row">
                        <label for="editFirstname">First Name:</label>
                    </div>
                    <input type="text" id="editFirstname" name="firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="editMiddlename">Middle Name</label>
                    <input type="text" id="editMiddlename" name="middlename" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editLastname">Last Name:</label>
                    </div>
                    <input type="text" id="editLastname" name="lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="editSuffix">Suffix</label>
                    <input type="text" id="editSuffix" name="suffix" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editBirthdate">Birthdate:</label>
                    </div>
                    <input type="date" id="editBirthdate" name="birthdate" max="2020-01-01" class="input-text" onchange="calculateEditAge()" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editAge">Age:</label>
                    </div>
                    <input type="number" id="editAge" name="age" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editGender">Gender:</label>
                    </div>
                    <select class="input-text" id="editGender" name="gender" disabled>
                        <option value="" disabled selected> </option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="LGBTQ+">LGBTQ+</option>
                    </select>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editContact">Contact:</label>
                    </div>
                    <input type="text" id="editContact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" disabled>
                </div>


                <div class="container-input-100">
                    <div class="row">
                        <label for="editEmail">Email:</label>
                    </div>
                    <input type="email" id="editEmail" name="email" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editHouse">House No./ Unit No. / Floor:</label>
                    </div>
                    <input type="text" id="editHouse" name="editHouse" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>


                <div class="container-input-49">
                    <div class="row">
                        <label for="editBuilding">Building:</label>
                    </div>
                    <input type="text" id="editBuilding" name="editBuilding" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>


                <div class="container-input-49">
                    <div class="row">
                        <label for="editStreet">Street:</label>
                    </div>
                    <input type="text" id="editStreet" name="editStreet" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>


                <div class="container-input-49">
                    <div class="row">
                        <label for="editBarangay">Barangay:</label>
                    </div>
                    <input type="text" id="editBarangay" name="editBarangay" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>




                <div class="container-input-49">
                    <div class="row">
                        <label for="editCompanyName">Company Name:</label>
                    </div>
                    <input type="text" id="editCompanyName" name="company_name" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row">
                        <label for="editCompanyContact">Company Contact:</label>
                    </div>
                    <input type="text" id="editCompanyContact" name="company_contact" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-100">
                    <div class="row">
                        <label for="editCompanyAddress">Company Address:</label>
                    </div>
                    <input type="text" id="editCompanyAddress" name="company_address" class="input-text" autocomplete="off" disabled>
                </div>



                <div class="container-input-49">
                    <label for="imageEditPatronsPreview">Patrons Image</label>
                    <div class="form-patrons">
                        <img src="../images/no-image.png" alt="" class="image-contain" id="imageEditPatronsPreview">
                    </div>
                </div>

                <div class="container-input-49">
                    <label for="imageValidIDPreview">Valid ID</label>
                    <div class="form-patrons">
                        <img src="../images/no-image.png" alt="" class="image-contain" id="imageValidIDPreview">
                    </div>
                </div>


                <div class="container-input-100"></div>


                <div class="title-26px container-input-100">
                    Guarantor Information
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editGuarantorFirstname">Guarantor First Name:</label>
                    </div>
                    <input type="text" id="editGuarantorFirstname" name="firstname" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="editGuarantorMiddlename">Guarantor Middle Name</label>
                    <input type="text" id="editGuarantorMiddlename" name="middlename" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editGuarantorLastname">Guarantor Last Name:</label>
                    </div>
                    <input type="text" id="editGuarantorLastname" name="lastname" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <label for="editGuarantorSuffix">Guarantor Suffix</label>
                    <input type="text" id="editGuarantorSuffix" name="suffix" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editGuarantorContact">Guarantor Contact:</label>
                    </div>
                    <input type="text" id="editGuarantorContact" name="contact" class="input-text" oninput="handleInput(this)" placeholder="+63xxxxxxxxxx" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editGuarantorAddress">Guarantor Address:</label>
                    </div>
                    <input type="text" id="editGuarantorAddress" name="address" class="input-text" oninput="capitalize(this)" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editGuarantorCompanyName">GuarantorCompany Name:</label>
                    </div>
                    <input type="text" id="editGuarantorCompanyName" name="company_name" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-49">
                    <div class="row row-between">
                        <label for="editCompanyContact">Guarantor Company Contact:</label>
                    </div>
                    <input type="text" id="editGuarantorCompanyContact" name="company_contact" class="input-text" autocomplete="off" disabled>
                </div>

                <div class="container-input-100">
                    <div class="row row-between">
                        <label for="editGuarantorCompanyAddress">Guarantor Company Address:</label>
                    </div>
                    <input type="text" id="editGuarantorCompanyAddress" name="company_address" class="input-text" autocomplete="off" disabled>
                </div>


                <div class="container-input-49">
                    <label for="imageGuarantorSignPreview">Guarantor Sign</label>
                    <div class="form-patrons">
                        <img src="../images/no-image.png" alt="" class="image-contain" id="imageGuarantorSignPreview">
                    </div>
                </div>


                <div class="container-input-49">
                    <label for="imageSignPreview">Patron Sign</label>
                    <div class="form-patrons">
                        <img src="../images/no-image.png" alt="" class="image-contain" id="imageSignPreview">
                    </div>
                </div>

            </div>

        </div>



    </div>
</div>


<script>
    function openViewModal(element) {
        // Retrieve data attributes from the clicked element
        const patronId = decodeURIComponent(element.getAttribute('data-patrons-id'));
        const firstname = decodeURIComponent(element.getAttribute('data-firstname'));
        const middlename = decodeURIComponent(element.getAttribute('data-middlename'));
        const lastname = decodeURIComponent(element.getAttribute('data-lastname'));
        const suffix = decodeURIComponent(element.getAttribute('data-suffix'));
        const birthdate = decodeURIComponent(element.getAttribute('data-birthdate'));
        const age = decodeURIComponent(element.getAttribute('data-age'));
        const gender = decodeURIComponent(element.getAttribute('data-gender'));
        const contact = decodeURIComponent(element.getAttribute('data-contact'));
        const house_num = decodeURIComponent(element.getAttribute('data-house-num'));
        const building = decodeURIComponent(element.getAttribute('data-building'));
        const street = decodeURIComponent(element.getAttribute('data-street'));
        const barangay = decodeURIComponent(element.getAttribute('data-barangay'));
        const companyName = decodeURIComponent(element.getAttribute('data-company-name'));
        const companyContact = decodeURIComponent(element.getAttribute('data-company-contact'));
        const companyAddress = decodeURIComponent(element.getAttribute('data-company-address'));
        const email = decodeURIComponent(element.getAttribute('data-email'));
        const image = decodeURIComponent(element.getAttribute('data-image'));
        const sign = decodeURIComponent(element.getAttribute('data-sign'));
        const validId = decodeURIComponent(element.getAttribute('data-valid-id'));
        const guarantorId = decodeURIComponent(element.getAttribute('data-guarantor-id'));
        const guarantorFirstname = decodeURIComponent(element.getAttribute('data-guarantor-firstname'));
        const guarantorMiddlename = decodeURIComponent(element.getAttribute('data-guarantor-middlename'));
        const guarantorLastname = decodeURIComponent(element.getAttribute('data-guarantor-lastname'));
        const guarantorSuffix = decodeURIComponent(element.getAttribute('data-guarantor-suffix'));
        const guarantorContact = decodeURIComponent(element.getAttribute('data-guarantor-contact'));
        const guarantorAddress = decodeURIComponent(element.getAttribute('data-guarantor-address'));
        const guarantorCompanyName = decodeURIComponent(element.getAttribute('data-guarantor-company-name'));
        const guarantorCompanyContact = decodeURIComponent(element.getAttribute('data-guarantor-company-contact'));
        const guarantorCompanyAddress = decodeURIComponent(element.getAttribute('data-guarantor-company-address'));
        const guarantorSign = decodeURIComponent(element.getAttribute('data-guarantor-sign'));


        // Show the modal
        document.getElementById('viewModal').classList.add('show');

        // Populate the modal fields with the decoded values
        document.getElementById('editPatronId').value = patronId;
        document.getElementById('editFirstname').value = firstname;
        document.getElementById('editMiddlename').value = middlename;
        document.getElementById('editLastname').value = lastname;
        document.getElementById('editSuffix').value = suffix;
        document.getElementById('editBirthdate').value = birthdate;
        document.getElementById('editAge').value = age;
        document.getElementById('editGender').value = gender;
        document.getElementById('editContact').value = contact;
        document.getElementById('editHouse').value = house_num;
        document.getElementById('editBuilding').value = building;
        document.getElementById('editStreet').value = street;
        document.getElementById('editBarangay').value = barangay;
        document.getElementById('editCompanyName').value = companyName;
        document.getElementById('editCompanyContact').value = companyContact;
        document.getElementById('editCompanyAddress').value = companyAddress;
        document.getElementById('editEmail').value = email;
        document.getElementById('editGuarantorId').value = guarantorId;
        document.getElementById('editGuarantorFirstname').value = guarantorFirstname;
        document.getElementById('editGuarantorMiddlename').value = guarantorMiddlename;
        document.getElementById('editGuarantorLastname').value = guarantorLastname;
        document.getElementById('editGuarantorSuffix').value = guarantorSuffix;
        document.getElementById('editGuarantorContact').value = guarantorContact;
        document.getElementById('editGuarantorAddress').value = guarantorAddress;
        document.getElementById('editGuarantorCompanyName').value = guarantorCompanyName;
        document.getElementById('editGuarantorCompanyContact').value = guarantorCompanyContact;
        document.getElementById('editGuarantorCompanyAddress').value = guarantorCompanyAddress;


        // Set the image preview
        document.getElementById('imageEditPatronsPreview').src = '../patron_images/' + image;

        document.getElementById('imageValidIDPreview').src = '../validID_images/' + validId;

        document.getElementById('imageSignPreview').src = '../sign_images/' + sign;

        document.getElementById('imageGuarantorSignPreview').src = '../sign_images/' + guarantorSign;


        // Clear the file input to allow a new selection
        document.getElementById('edit_image').value = '';
    }



    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');
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


<script src="js/close-status.js"></script>