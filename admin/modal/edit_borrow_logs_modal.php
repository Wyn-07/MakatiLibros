<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Borrow Logs Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/edit_borrow_logs.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">

            <div class="container-form">

                <div class="container-input">
                    <input type="hidden" id="editLogId" name="log_id" class="input-text" autocomplete="off">

                    <div class="container-input-49">
                        <label for="editDate">Date:</label>
                        <input type="text" id="editDate" name="date" class="input-text" autocomplete="off" readonly>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editName">Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editName" name="name" class="input-text" autocomplete="off" required>
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
                            <label for="editCategory">Category:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input id="editCategory" name="category" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editBookTitle">Book Title:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editBookTitle" name="title" class="input-text" autocomplete="off" required>
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
    function openEditModal(log_id, log_date, category, book_title, name, age, gender, barangay, city) {

        document.getElementById('editModal').classList.add('show');

        document.getElementById('editLogId').value = log_id;
        document.getElementById('editDate').value = log_date;
        document.getElementById('editCategory').value = category;
        document.getElementById('editBookTitle').value = book_title;
        document.getElementById('editName').value = name;
        document.getElementById('editAge').value = age;
        document.getElementById('editGender').value = gender;
        document.getElementById('editBarangay').value = barangay;
        document.getElementById('editCity').value = city;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>