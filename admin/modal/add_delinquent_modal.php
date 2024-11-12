<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add Delinquent
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_delinquent.php" method="POST" enctype="multipart/form-data" id="form">

            <div class="container-form">

                <div class="container-input">


                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="borrow_id">Borrow ID</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="borrow_id" id="borrow_id" class="input-text" autocomplete="off" required>
                    </div>


                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="patron">Patron Name</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="patron" class="input-text" id="patronInput" autocomplete="off" required disabled>
                        <input type="hidden" name="patron_id" class="input-text" id="patronIdInput" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="title">Book Title</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="title" class="input-text" id="titleInput" autocomplete="off" required disabled>
                        <input type="hidden" name="book_id" class="input-text" id="book_id" autocomplete="off" required>
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
</script>