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

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="name">Patron Name</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="name" class="input-text" id="nameInput" autocomplete="off" required>
                    </div>

                    <div class="container-input-100">
                        <div class="row row-between">
                            <label for="title">Book Title</label>
                            <div class="container-asterisk">
                                <img src="../images/asterisk-red.png" class="image">
                            </div>
                        </div>
                        <input type="text" name="title" class="input-text" id="titleInput" autocomplete="off" required>
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