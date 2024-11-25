<div id="viewModal" class="modal">
    <div class="modal-content-big">

        <div class="row row-between">
            <div class="title-26px">
                View | Audit
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>

        <div class="container-form">

            <div class="container-input">
                <input type="hidden" id="view_log_id" class="input-text">

                <div class="container-input-49">
                    <label for="view_date_time">DateTime</label>
                    <div id="view_date_time" class="input-text"></div>
                </div>

                <div class="container-input-49">
                    <label for="view_librarian">Modified By</label>
                    <div id="view_librarian" class="input-text"></div>
                </div>

                <div class="container-input-49">
                    <label for="view_page">Page</label>
                    <div id="view_page"  class="input-text"></div>
                </div>

                <div class="container-input-49">
                    <label for="view_description">Description</label>
                    <div id="view_description" class="input-text"></div>
                </div>

                <div class="container-input-49">
                    <label for="view_old_data">Old Data</label>
                    <div id="view_old_data" class="textarea-data"></div>
                </div>

                <div class="container-input-49">
                    <label for="view_new_data">New Data</label>
                    <div id="view_new_data" class="textarea-data"></div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    function openViewModal(element) {

        const logId = decodeURIComponent(element.getAttribute("data-log-id"));
        const dateTime = decodeURIComponent(element.getAttribute("data-date-time"));
        const oldData = decodeURIComponent(element.getAttribute("data-old-data"));
        const newData = decodeURIComponent(element.getAttribute("data-new-data"));
        const librarianName = decodeURIComponent(element.getAttribute("data-librarian"));
        const page = decodeURIComponent(element.getAttribute("data-page"));
        const description = decodeURIComponent(element.getAttribute("data-description"));

        // Show the modal
        document.getElementById('viewModal').classList.add('show');

        // Populate the modal fields with the decoded data
        document.getElementById('view_log_id').textContent  = logId;
        document.getElementById('view_date_time').textContent  = dateTime;
        document.getElementById('view_old_data').innerHTML   = oldData;
        document.getElementById('view_new_data').innerHTML   = newData;
        document.getElementById('view_librarian').textContent  = librarianName;
        document.getElementById('view_page').textContent  = page;
        document.getElementById('view_description').textContent  = description;

    }


    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');

    }

    function saveChanges() {
        closeviewModal();
    }
</script>