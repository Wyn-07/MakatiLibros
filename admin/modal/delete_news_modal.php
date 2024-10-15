<div id="deleteModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Delete | News
            </div>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>

        <form action="functions/delete_news.php" method="POST">
            <div class="container-form">

                <input type="hidden" name="news_id" id="news_id">

                <div style="text-align: center; margin-bottom: 10px;">
                    Are you sure you want to delete?
                </div>


                <div class="row row-center">
                    <button type="button" name="cancel" class="button-cancel" onclick="closeDeleteModal()">No</button>
                    <button type="submit" name="submit" class="button-submit">Yes</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    function openDeleteModal(newsId) {
        document.getElementById('deleteModal').classList.add('show');

        document.getElementById('news_id').value = newsId;

    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');

    }

    function saveChanges() {
        openDeleteModal();
    }
</script>