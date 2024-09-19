<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Add | Return
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <div class="container-form">

                <div class="container-input-100">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="input-text" id="nameInput" autocomplete="off" required>
                </div>

                <div class="container-input-100">
                    <label for="title">Book Title</label>
                    <input type="text" id="titleInput" name="title" class="input-text" autocomplete="off" required>
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