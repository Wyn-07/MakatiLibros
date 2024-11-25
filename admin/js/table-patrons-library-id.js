let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalPatrons = 0;
let sortPatronColumn = 'card_id';
let sortPatronOrder = 'desc';
let searchQuery = "";

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['patrons_name', 'card_id', 'date_issued', 'valid_until'];
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
    fetch(`functions/fetch_patrons_library_id_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortPatronColumn=${sortPatronColumn}&sortPatronOrder=${sortPatronOrder}`)
        .then(response => response.json())
        .then(data => {
            totalPatrons = data.totalPatrons; // Update total patrons count
            document.getElementById("card-table-container").innerHTML = generateTable(data.patronList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching patrons:", error));
}



function generateTable(patronList) {
    // Adjust the column names to reflect patron_name, card_id, and guarantor_name
    const columnNames = ['patrons_name', 'card_id', 'date_issued', 'valid_until'];

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
                        <div class="column-title">EDIT</div>
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
                <td>${patron.patrons_name}</td> 
                <td>${patron.card_id}</td> 
                <td>${patron.date_issued}</td>
                <td>${patron.valid_until}</td>
                <td>
                    <div class="td-center">
                        <div class="button-view" 
                            data-patrons-id="${encodeURIComponent(patron.patrons_id)}"
                            data-name="${encodeURIComponent(patron.patron_firstname)} ${encodeURIComponent(patron.patron_middlename)} ${encodeURIComponent(patron.patron_lastname)} ${encodeURIComponent(patron.patron_suffix)}"
                            data-patron-address="${encodeURIComponent(`${patron.patron_house_num} ${patron.patron_building && patron.patron_building.trim() !== '' ? patron.patron_building + ' ' : ''}${patron.patron_street} ${patron.patron_barangay}`.trim())}"
                            data-patron-company-name="${encodeURIComponent(patron.patron_company_name)}"
                            data-card-id="${encodeURIComponent(patron.card_id)}"
                            data-valid-until="${encodeURIComponent(patron.valid_until)}"
                            data-patron-image="${encodeURIComponent(patron.patron_image)}"
                            onclick="openViewModal(this)">
                            <img src="../images/view-white.png" class="image">
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
