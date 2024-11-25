let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalPatrons = 0;
let sortPatronColumn = 'card_id';
let sortPatronOrder = 'desc';
let searchQuery = "";

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['patrons_name', 'card_id', 'guarantor', 'patron_status'];
    sortPatronColumn = columnNames[columnIndex];

    // Toggle the sort order based on current order
    if (sortPatronOrder === 'asc') {
        sortPatronOrder = 'desc';
    } else {
        sortPatronOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateSortIcon(columnIndex);

    // Reload the patrons with the new sorting parameters
    loadPatrons(currentPage, rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder);
}

function updateSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);

    if (sortPatronOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";
    } else {
        currentSortIcon.src = "../images/dsc.png";
    }
}


function loadPatrons(page = 1, itemsPerPage = 5, searchQuery = "", sortPatronColumn = 'patrons_name', sortPatronOrder = '') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    // Fetch patrons with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_patrons_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortPatronColumn=${sortPatronColumn}&sortPatronOrder=${sortPatronOrder}`)
        .then(response => response.json())
        .then(data => {
            totalPatrons = data.totalPatrons; // Update total patrons count
            document.getElementById("patron-table-container").innerHTML = generateTable(data.patronList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching patrons:", error));
}



function generateTable(patronList) {
    // Adjust the column names to reflect patron_name, card_id, and guarantor_name
    const columnNames = ['patrons_name', 'card_id', 'guarantor', 'patron_status'];

    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${sortPatronColumn === col
            ? (sortPatronOrder === 'asc' ? 'asc.png' : 'dsc.png')
            : 'sort.png'
        }" class="sort">
                            </div>
                        </th>
                    `).join('')}
                    <th>
                        <div class="column-title">VIEW</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                ${generateTableRows(patronList)}
            </tbody>
        </table>
    `;

    return tableHeader;
}

function generateTableRows(patronList) {
    if (patronList.length === 0) {
        return `
            <tr>
                <td colspan="6">
                    <div class="no-result">
                        <div class="no-result-image">
                            <img src="../images/no-result.jpg" alt="No Results Found" class="image" />
                        </div>
                        <p>No results found.</p>
                    </div>
                </td>
            </tr>
        `;
    }

    return patronList.map(patron => {
        return `
            <tr>
                <td>${patron.patrons_name}</td> <!-- Display patron name in one column -->
                <td>${patron.card_id}</td> <!-- Display card ID -->
                <td>${patron.guarantor}</td> <!-- Display guarantor name -->
                <td>
                    <center>
                        <div class="status ${patron.patron_status.toLowerCase()}">${patron.patron_status}</div>
                    </center>
                </td>
                <td>
                    <div class="td-center">
                        <div class="button-view" 
                                data-patrons-id="${encodeURIComponent(patron.patrons_id)}"
                                data-firstname="${encodeURIComponent(patron.patron_firstname)}"
                                data-middlename="${encodeURIComponent(patron.patron_middlename)}"
                                data-lastname="${encodeURIComponent(patron.patron_lastname)}"
                                data-suffix="${encodeURIComponent(patron.patron_suffix)}"
                                data-birthdate="${encodeURIComponent(patron.patron_birthdate)}"
                                data-age="${encodeURIComponent(patron.patron_age)}"
                                data-gender="${encodeURIComponent(patron.patron_gender)}"
                                data-contact="${encodeURIComponent(patron.patron_contact)}"
                                data-house-num="${encodeURIComponent(patron.patron_house_num)}"
                                data-building="${encodeURIComponent(patron.patron_building)}"
                                data-street="${encodeURIComponent(patron.patron_street)}"
                                data-barangay="${encodeURIComponent(patron.patron_barangay)}"
                                data-company-name="${encodeURIComponent(patron.patron_company_name)}"
                                data-company-contact="${encodeURIComponent(patron.patron_company_contact)}"
                                data-company-address="${encodeURIComponent(patron.patron_company_address)}"
                                data-email="${encodeURIComponent(patron.patron_email)}"
                                data-image="${encodeURIComponent(patron.patron_image)}"
                                data-sign="${encodeURIComponent(patron.patron_sign)}"
                                data-valid-id="${encodeURIComponent(patron.valid_id)}"
                                data-guarantor-id="${encodeURIComponent(patron.guarantor_id)}"
                                data-guarantor-firstname="${encodeURIComponent(patron.guarantor_firstname)}"
                                data-guarantor-middlename="${encodeURIComponent(patron.guarantor_middlename)}"
                                data-guarantor-lastname="${encodeURIComponent(patron.guarantor_lastname)}"
                                data-guarantor-suffix="${encodeURIComponent(patron.guarantor_suffix)}"
                                data-guarantor-contact="${encodeURIComponent(patron.guarantor_contact)}"
                                data-guarantor-address="${encodeURIComponent(patron.guarantor_address)}"
                                data-guarantor-company-name="${encodeURIComponent(patron.guarantor_company_name)}"
                                data-guarantor-company-contact="${encodeURIComponent(patron.guarantor_company_contact)}"
                                data-guarantor-company-address="${encodeURIComponent(patron.guarantor_company_address)}"
                                data-guarantor-sign="${encodeURIComponent(patron.guarantor_sign)}"
                                data-card-id="${encodeURIComponent(patron.card_id)}"
                                data-date-issued="${encodeURIComponent(patron.date_issued)}"
                                data-valid-until="${encodeURIComponent(patron.valid_until)}"
                                onclick="openViewModal(this)">
                                <img src="../images/view-white.png" class="image">
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePaginationControls() {
    const pagination = document.getElementById("pagination");
    const entryInfo = document.getElementById("entry-info");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalPatrons / rowsPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalPatrons);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalPatrons} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadPatrons(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder); // Include searchQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadPatrons(i, rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder); // Include searchQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadPatrons(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder); // Include searchQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    loadPatrons(1, rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder);  // Include searchQuery
});

// Initial load
loadPatrons(currentPage, rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder); // Default sort from DB

function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadPatrons(currentPage, rowsPerPage, searchQuery, sortPatronColumn, sortPatronOrder); // Default sort from DB
}
