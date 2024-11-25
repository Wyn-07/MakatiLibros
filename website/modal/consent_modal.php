<!-- Modal Structure -->
<div id="consentModal" class="modal">
    <div class="modal-content">
        <div class="row row-between">
            <div class="title-26px">DATA PRIVACY CONSENT</div>
            <span class="modal-close" onclick="closeConsentModal()">&times;</span>
        </div>

        <div>
            In Compliance with the Data Privacy Act (DPA/R.P 10173) of 2012, and its implementing Rules and Regulations (IRR) effective since September 8, 2016, I allow the MAKATI CITY LIBRARY under the EDUCATION DEPARTMENT of the City Government of Makati to provide me certain services in relation to my application for the Makati City Library Card.
            <br>
            As such, I agree and authorize them to:
            <br>
            1. Collect and use my personal information for the purpose stated above and any other legal purposes.
            <br>
            2. Retain and store my information for a certain period as prescribed by law. My information will be deleted/destroyed after this period.
            <br>
            3. Share my information with other offices/departments within the City Government of Makati and necessary third parties for legitimate purposes. I am assured that security systems are employed to protect my information.
            <br>
            4. Allow only myself or a duly authorized representative (with a Special Power of Attorney) to view, change, or recover my personal information.
            <br>
            5. Inform me of future services or projects offered by the City Government of Makati using the personal information I shared.
        </div>

        <div class="row row-right">
            <button type="submit" name="submit" class="button button-submit">Agree</button>
        </div>
    </div>
</div>

<script>
    function openConsentModal() {
        const modal = document.getElementById('consentModal');
        modal.classList.add('show');
    }

    function closeConsentModal() {
        const modal = document.getElementById('consentModal');
        modal.classList.remove('show');
    }


</script>
