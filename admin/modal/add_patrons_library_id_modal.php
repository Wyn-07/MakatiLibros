<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add Patron Libray ID Information
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_patrons_library_id.php" method="POST" enctype="multipart/form-data" id="form">

            <div class="container-form">

                <div class="container-input">

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


                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="guarantor">Guarantor Name</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="guarantor" class="input-text" id="guarantorInput" autocomplete="off" required>
                        <input type="hidden" name="guarantor_id" class="input-text" id="guarantorIdInput" autocomplete="off" required>
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