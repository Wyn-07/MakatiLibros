<div id="addModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Add Patron Logs Information
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_patron_logs.php" method="POST">

            <div class="container-form">

                <div class="container-input">

                    <div class="container-input-49">
                        <label for="addDate">Date:</label>
                        <input type="text" id="addDate" name="log_date" class="input-text" autocomplete="off" readonly>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addPurpose">Purpose:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addPurpose" name="purpose" class="input-text" autocomplete="off" required>
                    </div>

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
                            <label for="addAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="addAge" name="age" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addGender">Gender</label>
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
                            <label for="addBarangay">Barangay:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addBarangay" name="barangay" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addCity">City:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="addCity" name="city" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="addSector">Sector</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="addSector" name="sector" required>
                            <option value="" disabled selected> </option>
                            <option value="Student">Student</option>
                            <option value="Professional">Professional</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="PWD">PWD</option>
                        </select>
                    </div>


                    <div class="container-input-49">
                        <label for="addSectorDetails">School and Course / Profession and Office</label>
                        <textarea id="addSectorDetails" name="sector_details" class="input-text" autocomplete="off"></textarea>
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

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        document.getElementById('addDate').value = formattedDate;
    });
</script>