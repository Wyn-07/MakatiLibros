<div id="editModal" class="modal">
    <div class="modal-content">

        <div class="row row-between">
            <div class="title-26px">
                Edit | Delinquent
            </div>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>

        <form action="functions/update_delinquent.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
            <input type="hidden" id="editDelinquentId" name="editDelinquentId">
            <div class="container-form">

                <div>
                    <label for="status">Status</label>
                    <select class="input-text" id="editStatus" name="editStatus" required>
                        <option value="Unresolved">Unresolved</option>
                        <option value="Resolved">Resolved</option>
                    </select>
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
        const delinquentId = decodeURIComponent(element.getAttribute("data-delinquent-id"));
        const delinquentStatus = decodeURIComponent(element.getAttribute("data-status"));

        document.getElementById('editModal').classList.add('show');
        document.getElementById('editDelinquentId').value = delinquentId;
        document.getElementById('editStatus').value = delinquentStatus;
    }


    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');

    }

    function saveChanges() {
        closeEditModal();
    }
</script>