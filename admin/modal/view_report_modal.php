<div id="viewModal" class="modal">
    <div class="modal-content-medium">

        <div class="row row-between">
            <div class="title-26px">
                View | Report
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>



        <div>
            table here
        </div>



    </div>
</div>

<script>
    function openViewModal(element) {
        const date = decodeURIComponent(element.getAttribute("data-date"));
        const bookTitle= decodeURIComponent(element.getAttribute("data-book-title"));
        const patron= decodeURIComponent(element.getAttribute("data-patron"));


        document.getElementById('viewModal').classList.add('show');

    

    }


    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');

    }

    function saveChanges() {
        closeviewModal();
    }
</script>