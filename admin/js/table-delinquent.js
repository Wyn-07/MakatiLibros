let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalDelinquent = 0;
let sortDelinquentColumn = 'borrow_date';  
let sortDelinquentOrder = 'desc'; 
let searchQuery = "";  

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['patrons_name', 'borrow_date', 'title', 'status', 'edit'];
    sortDelinquentColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortDelinquentOrder === 'asc') {
        sortDelinquentOrder = 'desc';
    } else {
        sortDelinquentOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadDelinquent(currentPage, rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder);
}


function updateSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortDelinquentOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadDelinquent(page = 1, itemsPerPage = 5, searchQuery = "", sortDelinquentColumn = 'borrow_id', sortDelinquentOrder = '') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    fetch(`functions/fetch_delinquent_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortDelinquentColumn=${sortDelinquentColumn}&sortDelinquentOrder=${sortDelinquentOrder}`)
        .then(response => response.json())
        .then(data => {
            totalDelinquent = data.totalDelinquent; 
            document.getElementById("delinquent-table-container").innerHTML = generateTable(data.delinquentList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching delinquent:", error));
}


function generateTable(delinquentList) {
    const columnNames = ['patrons_name', 'borrow_date', 'title', 'status', 'edit'];
    
    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortDelinquentColumn === col 
                                        ? (sortDelinquentOrder === 'asc' ? 'asc.png' : 'dsc.png') 
                                        : 'sort.png'
                                }" class="sort">
                            </div>
                        </th>
                    `).join('')}
                </tr>
            </thead>
            <tbody>
                ${generateTableRows(delinquentList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generateTableRows(delinquentList) {
    if (delinquentList.length === 0) {
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

    return delinquentList.map(delinquent => {
        return `
            <tr>
                <td>${delinquent.patrons_name}</td> 
                <td>${delinquent.borrow_date} ${delinquent.borrow_time}</td>
                <td>${delinquent.title}</td>
                <td>${delinquent.status}</td>
                <td>
                    <div class="td-center">
                        <div class="button-edit" 
                             data-delinquent-id="${delinquent.delinquent_id}"  
                             data-status="${delinquent.status}" 
                             onclick="openEditModal(this)">
                            <img src="../images/edit-white.png" class="image">
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

    const totalPages = Math.ceil(totalDelinquent / rowsPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalDelinquent);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalDelinquent} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadDelinquent(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder); // Include searchQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadDelinquent(i, rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder); // Include searchQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadDelinquent(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder); // Include searchQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    loadDelinquent(1, rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder);  // Include searchQuery
});


// Initial load
loadDelinquent(currentPage, rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder); // Default sort from DB


function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadDelinquent(currentPage, rowsPerPage, searchQuery, sortDelinquentColumn, sortDelinquentOrder); // Default sort from DB
}