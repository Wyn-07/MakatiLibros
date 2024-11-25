<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Patron Logs Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_patron_logs.php" method="POST">

            <div class="container-form">

            <input type="hidden" id="editLogId" name="log_id" class="input-text" autocomplete="off" readonly>

                <div class="container-input">
                
                    <div class="container-input-49">
                        <label for="editDate">Date:</label>
                        <input type="date" id="editDate" name="log_date" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editPurpose">Purpose:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editPurpose" name="purpose" class="input-text" autocomplete="off" required>
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
                            <label for="editAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="editAge" name="age" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editGender">Gender</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="editGender" name="gender" required>
                            <option value="" disabled selected> </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="LGBTQ+">LGBTQ+</option>
                        </select>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editBarangay">Barangay:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editBarangay" name="barangay" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editCity">City:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editCity" name="city" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editSector">Sector</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="editSector" name="sector" required>
                            <option value="" disabled selected> </option>
                            <option value="Student">Student</option>
                            <option value="Professional">Professional</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="PWD">PWD</option>
                        </select>
                    </div>


                    <div class="container-input-49">
                        <label for="editSectorDetails">School and Course / Profession and Office</label>
                        <textarea id="editSectorDetails" name="sector_details" class="input-text" autocomplete="off"></textarea>
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
function openEditModal(element) {
    
    const logId = decodeURIComponent(element.getAttribute("data-logs-id"));
    const logDate = decodeURIComponent(element.getAttribute("data-logs-date"));
    const firstname = decodeURIComponent(element.getAttribute("data-logs-firstname"));
    const middlename = decodeURIComponent(element.getAttribute("data-logs-middlename"));
    const lastname = decodeURIComponent(element.getAttribute("data-logs-lastname"));
    const suffix = decodeURIComponent(element.getAttribute("data-logs-suffix"));
    const age = decodeURIComponent(element.getAttribute("data-logs-age"));
    const gender = decodeURIComponent(element.getAttribute("data-logs-gender"));
    const barangay = decodeURIComponent(element.getAttribute("data-logs-barangay"));
    const city = decodeURIComponent(element.getAttribute("data-logs-city"));
    const purpose = decodeURIComponent(element.getAttribute("data-logs-purpose"));
    const sector = decodeURIComponent(element.getAttribute("data-logs-sector"));
    const sectorDetails = decodeURIComponent(element.getAttribute("data-logs-sector-details"));
    
    
    // Get the modal element
    const modal = document.getElementById('editModal');

    // Populate the form fields
    document.getElementById('editLogId').value = logId;
    document.getElementById('editDate').value = logDate;

    document.getElementById('editFirstname').value = firstname;
    document.getElementById('editMiddlename').value = middlename;
    document.getElementById('editLastname').value = lastname;
    document.getElementById('editSuffix').value = suffix;
    document.getElementById('editAge').value = age;
    document.getElementById('editGender').value = gender;
    document.getElementById('editBarangay').value = barangay;
    document.getElementById('editCity').value = city;
    document.getElementById('editPurpose').value = purpose;
    document.getElementById('editSector').value = sector;
    document.getElementById('editSectorDetails').value = sectorDetails;

    // Show the modal
    modal.classList.add('show');
}


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    document.editEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        document.getElementById('editDate').value = formattedDate;
    });
</script>