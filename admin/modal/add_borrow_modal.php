<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add | Borrow
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_borrow.php" method="POST" enctype="multipart/form-data" id="form">
            <div class="container-form">

                <div class="container-input">

                    <input type="hidden" name="book_id" class="input-text" id="book_id" autocomplete="off" required>
                    <input type="hidden" name="status" class="input-text" id="status" autocomplete="off" value="Borrowing" required>


                    <!-- <label for="borrow_type">Borrow Type</label> <br>
                    <div class="row row-between">
                        <label>
                            <input type="radio" name="borrowType" value="circulation" required onclick="loadScript(this.value)">
                            Circulation
                        </label>
                        <label>
                            <input type="radio" name="borrowType" value="non-circulation" onclick="loadScript(this.value)">
                            Non-Circulation
                        </label>
                    </div> -->


                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="acc_num">Acc Number</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="acc_num" class="input-text" id="acc_num" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="class_num">Class Number</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="class_num" class="input-text" id="class_num" autocomplete="off" required disabled>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="title">Book Title</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="title" class="input-text" id="titleInput" autocomplete="off" required disabled>
                    </div>


                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="patron">Patron Name</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="patron" class="input-text" id="patronInput" autocomplete="off" required>
                        <input type="hidden" name="patron_id" class="input-text" id="patronIdInput" autocomplete="off" required>
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

    function saveChanges() {
        closeAddModal();
    }
</script>