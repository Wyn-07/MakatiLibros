<div id="addModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                Add | Condemned Book
            </div>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>

        <form action="functions/add_condemned.php" method="POST" enctype="multipart/form-data" id="form">

            <div class="row">
                <div class="modal-content-left">
                    <div class="container-form">

                        <div class="container-input-100">
                            <label for="acc_num">Acc Number</label>
                            <input type="text" id="acc_num" name="acc_num" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input-100">
                            <label for="class_num">Class Number</label>
                            <input type="text" id="class_num" name="class_num" class="input-text" autocomplete="off" readonly required>
                        </div>

                        <div class="container-input-100">
                            <label for="title">Book Title</label>
                            <input type="text" id="title" name="title" class="input-text" autocomplete="off" readonly required>
                        </div>


                        <div class="container-input-100">
                            <label for="author">Author</label>
                            <input type="text" id="author" name="author" class="input-text" autocomplete="off" readonly required>
                            <input type="hidden" id="author_id" name="author_id" class="input-text" autocomplete="off" required>

                        </div>


                        <div class="container-input-100">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" class="input-text" autocomplete="off" readonly required>
                            <input type="hidden" id="category_id" name="category_id" class="input-text" autocomplete="off" required>

                        </div>

                        <div class="container-input-100">
                            <label for="copyright">Copyright</label>
                            <input type="number" id="copyright" name="copyright" min="1000" class="input-text" autocomplete="off" readonly required>
                        </div>
                    </div>
                </div>


                <div class="modal-content-right">
                    <div class="column">
                        <label for="book_image">Book Image</label>
                        <div class="container-form-book">
                            <div class="form-book">
                                <img src="../book_images/no_image.png" class="image" id="imageBookPreview">
                            </div>
                        </div>
                        <input type="hidden" id="image" name="image">
                    </div>


                    <div class="row row-right">
                        <button type="submit" name="submit" class="button-submit">Submit</button>
                    </div>
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