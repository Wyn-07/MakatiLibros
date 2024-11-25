<div id="editModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                Edit Borrow Logs Information
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_borrow_logs.php" method="POST">

            <div class="container-form">

                <div class="container-input">
                    <input type="hidden" id="editLogId" name="log_id" class="input-text" autocomplete="off">

                    <div class="row">
                        <div class="container-input-49">
                            <label for="editDate">Date:</label>
                            <input type="date" id="editDate" name="date" class="input-text" autocomplete="off">
                        </div>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editFirstname">First Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editFirstname" name="editFirstname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editMiddlename">Middle Name</label>
                        <input type="text" id="editMiddlename" name="editMiddlename" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editLastname">Last Name:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editLastname" name="editLastname" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <label for="editSuffix">Suffix</label>
                        <input type="text" id="editSuffix" name="editSuffix" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editAge">Age:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="number" id="editAge" name="editAge" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editGender">Gender</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <select class="input-text" id="editGender" name="editGender" required>
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
                        <input type="text" id="editBarangay" name="editBarangay" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editCity">City:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editCity" name="editCity" class="input-text" autocomplete="off" required>
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editCategory">Category:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editBorrowLogCategory" name="editBorrowLogCategory" class="input-text" autocomplete="off">
                        <input type="hidden" id="editBorrowLogCategoryId" name="editBorrowLogCategoryId" class="input-text" autocomplete="off">
                    </div>

                    <div class="container-input-49">
                        <div class="row row-between">
                            <label for="editBookTitle">Book Title:</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" id="editBookTitle" name="editBookTitle" class="input-text" autocomplete="off" required>
                        <input type="hidden" id="editBookTitleId" name="editBookTitleId" class="input-text" autocomplete="off" required>
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
        // Retrieve data attributes from the clicked element
        const log_id = decodeURIComponent(element.getAttribute('data-logs-id'));
        const log_date = decodeURIComponent(element.getAttribute('data-logs-date'));
        const category = decodeURIComponent(element.getAttribute('data-logs-category'));
        const categoryId = decodeURIComponent(element.getAttribute('data-logs-categoryid'));
        const book_title = decodeURIComponent(element.getAttribute('data-logs-booktitle'));
        const bookId = decodeURIComponent(element.getAttribute('data-logs-bookid'));
        const firstname = decodeURIComponent(element.getAttribute('data-logs-firstname'));
        const middlename = decodeURIComponent(element.getAttribute('data-logs-middlename'));
        const lastname = decodeURIComponent(element.getAttribute('data-logs-lastname'));
        const suffix = decodeURIComponent(element.getAttribute('data-logs-suffix'));
        const age = decodeURIComponent(element.getAttribute('data-logs-age'));
        const gender = decodeURIComponent(element.getAttribute('data-logs-gender'));
        const barangay = decodeURIComponent(element.getAttribute('data-logs-barangay'));
        const city = decodeURIComponent(element.getAttribute('data-logs-city'));

        document.getElementById('editModal').classList.add('show');

        document.getElementById('editLogId').value = log_id;
        document.getElementById('editDate').value = log_date;
        document.getElementById('editBorrowLogCategory').value = category;
        document.getElementById('editBorrowLogCategoryId').value = categoryId;
        document.getElementById('editBookTitle').value = book_title;
        document.getElementById('editBookTitleId').value = bookId;
        document.getElementById('editFirstname').value = firstname;
        document.getElementById('editMiddlename').value = middlename;
        document.getElementById('editLastname').value = lastname;
        document.getElementById('editSuffix').value = suffix;
        document.getElementById('editAge').value = age;
        document.getElementById('editGender').value = gender;
        document.getElementById('editBarangay').value = barangay;
        document.getElementById('editCity').value = city;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>