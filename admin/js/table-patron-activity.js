let currentPatronPage = 1;
let rowPatronPerPage = parseInt(document.getElementById("entries-patrons").value);
let totalPatronLogs = 0;
let sortPatronColumn = 'date_time';  
let sortPatronOrder = 'desc'; 
let searchPatronQuery = "";  

function sortPatronTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['date_time', 'page', 'manage', 'librarian_name'];
    sortPatronColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortPatronOrder === 'asc') {
        sortPatronOrder = 'desc';
    } else {
        sortPatronOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updatePatronSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadPatron(currentPatronPage, rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder);
}


function updatePatronSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortPatronOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadPatron(page = 1, itemsPerPage = 5, searchPatronQuery = "", sortPatronColumn = 'date_time', sortPatronOrder = '') {
    currentPatronPage = page;
    rowPatronPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_patron_activity_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchPatronQuery)}&sortPatronColumn=${sortPatronColumn}&sortPatronOrder=${sortPatronOrder}`)
        .then(response => response.json())
        .then(data => {
            totalPatronLogs = data.totalPatronLogs; // Update total transactions count
            document.getElementById("patrons-table-container").innerHTML = generatePatronTable(data.patronList);
            updatePatronPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generatePatronTable(patronList) {
    const columnNames = ['date_time', 'page', 'manage', 'librarian_name'];
    
    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortPatronTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortPatronColumn === col 
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
                ${generatePatronTableRows(patronList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}

function generatePatronTableRows(patronList) {
    if (patronList.length === 0) {
        return `
            <tr>
                <td colspan="7">
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

    return patronList.map(log => {
        return `
            <tr>
                <td>${log.date_time}</td>
                <td>${log.page}</td>
                <td>${log.manage}</td> 
                <td>${log.librarian_name}</td> 
                <td>
                    <div class="td-center">
                        <div class="button-view" onclick="openViewModal('${log.logs_id}')">
                            <img src="../images/view-white.png" class="image">
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePatronPaginationControls() {
    const pagination = document.getElementById("pagination");
    const entryInfo = document.getElementById("entry-info");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalPatronLogs / rowPatronPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPatronPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPatronPage - 1) * rowPatronPerPage + 1;
    const endEntry = Math.min(currentPatronPage * rowPatronPerPage, totalPatronLogs);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalPatronLogs} entries-patrons`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadPatron(Math.max(currentPatronPage - pageGroupSize, 1), rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder); // Include searchPatronQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPatronPage ? "active" : "";
        pageButton.onclick = () => loadPatron(i, rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder); // Include searchPatronQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadPatron(Math.min(currentPatronPage + pageGroupSize, totalPages), rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder); // Include searchPatronQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-patrons").addEventListener("change", () => {
    rowPatronPerPage = parseInt(document.getElementById("entries-patrons").value);
    loadPatron(1, rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder);  // Include searchPatronQuery
});


// Initial load
loadPatron(currentPatronPage, rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder); // Default sort from DB


function searchPatronTable() {
    searchPatronQuery = document.getElementById("search-patrons").value;
    loadPatron(currentPatronPage, rowPatronPerPage, searchPatronQuery, sortPatronColumn, sortPatronOrder); // Default sort from DB
}