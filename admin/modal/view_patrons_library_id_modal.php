<div id="viewModal" class="modal">
    <div class="modal-content-id">

        <div class="row row-between">
            <div class="title-26px">
                View Patron Library ID
            </div>
            <span class="modal-close" onclick="closeViewModal()">&times;</span>
        </div>

        <div class="row row-right">
            <button class="button-print" onclick="window.print()">PRINT
                
            </button>
        </div>


        <div id="container-library-id" class="container-library-id">

            <div class="row row-between">
                <div class="id-logo-image">
                    <img src="../images/makaticity-logo.png" alt="" class="image">
                </div>
                <div class="container-id-header">
                    <div class="font-size-16" style="font-weight: bold">MAKATI CITY LIBRARY</div>
                    <div class="font-size-16">8th Floor, Makati City Hall Bldg 1</div>
                    <div class="font-size-14">J.P. Rizal St., Poblacion, Makati City, Tel. No. 8899-9071</div>
                </div>
                <div class="id-logo-image">
                    <img src="../images/library-logo.png" alt="" class="image">
                </div>
            </div>


            <div class="row">

                <div class="container-left-id">
                    <div class="row row-right font-size-16 id" style="font-weight: bold">I.D. No.: MCL-2024-XXXX</div>

                    <table class="table-id">
                        <tr class="tr-id">
                            <td class="td-id-none">Name:</td>
                            <td class="td-id-bottom name">Andrian Cuerdo</td>
                        </tr>
                        <tr class="tr-id">
                            <td class="td-id-none">Home Address:</td>
                            <td class="td-id-bottom address">4308 Montojo St., Sta. Cruz, Makati</td>
                        </tr>
                        <tr class="tr-id">
                            <td class="td-id-none">School/Company:</td>
                            <td class="td-id-bottom company">University of Makati</td>
                        </tr>
                    </table>

                    <div class="font-size-12">
                        Present this card each time you borrow any reading or libarary materials. You are responsible for library materials borrowed on this card.
                    </div>

                </div>

                <div class="container-right-id">
                    <div class="id-picture-image">
                        <img src="../patron_images/default_image.png" alt="" class="image" id="patronImage">
                    </div>
                    <div class="row-center font-size-10" style="font-weight: bold" id="validUntil"> Valid Until: </div>
                </div>

            </div>

            <div class="row row-between">

                <div class="id-bottom-row">
                    <div class="font-size-16">
                        Approved by:
                    </div>

                    <div>
                        <div class="id-librarian-name">
                            JENNIFER J. LALUNA
                        </div>
                        <div class="id-librarian-title">
                            Library Division Head
                        </div>
                    </div>
                </div>

                <div>
                    <div class="id-borrower-sign">
                        s
                    </div>
                    <div class="id-borrower-label">
                        Borrower's Signature
                    </div>
                </div>

            </div>

        </div>


    </div>
</div>


<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #container-library-id,
        #container-library-id * {
            visibility: visible;
        }

        .modal-content-id {
            width: 650px;
        }

        #container-library-id {
            width: 100%;
        }

        .modal-close,
        .button-submit {
            display: none;
        }
    }
</style>


<script>
    function openViewModal(element) {
        // Retrieve and decode values from data attributes
        const patronId = decodeURIComponent(element.getAttribute('data-patron-id'));
        const fullName = decodeURIComponent(element.getAttribute('data-name'));
        const address = decodeURIComponent(element.getAttribute('data-patron-address'));
        const companyName = decodeURIComponent(element.getAttribute('data-patron-company-name'));
        const validUntil = decodeURIComponent(element.getAttribute('data-valid-until'));
        const cardId = decodeURIComponent(element.getAttribute('data-card-id'));
        const image = decodeURIComponent(element.getAttribute('data-patron-image'));


        // Show the modal
        document.getElementById('viewModal').classList.add('show');

        // Select the elements in the modal to populate
        const idTd = document.querySelector('.row.row-right.font-size-16.id'); // Corrected selector
        const nameTd = document.querySelector('.td-id-bottom.name');
        const addressTd = document.querySelector('.td-id-bottom.address');
        const companyTd = document.querySelector('.td-id-bottom.company');

        if (patronId) {
            idTd.innerHTML = `I.D. No.: ${cardId}`;
        } else {
            idTd.innerHTML = 'No ID provided';
        }
        // Populate the name
        if (fullName && fullName.trim() !== '') {
            nameTd.innerHTML = fullName;
        } else {
            nameTd.innerHTML = 'No name provided';
        }

        // Populate the address
        if (address && address.trim() !== '') {
            addressTd.innerHTML = address;
        } else {
            addressTd.innerHTML = 'No address provided';
        }

        // Populate the company name
        if (companyName && companyName.trim() !== '') {
            companyTd.innerHTML = companyName;
        } else {
            companyTd.innerHTML = 'No company name provided';
        }


        var validUntilElement = document.getElementById("validUntil");
        validUntilElement.innerHTML = "Valid Until: " + validUntil;


        document.getElementById('patronImage').src = '../patron_images/' + image;


    }


    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');
    }
</script>