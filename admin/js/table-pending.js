let currentPendingPage = 1;
let rowsPendingPerPage = parseInt(document.getElementById("entries").value);
let totalPendingTransaction = 0;
let sortPendingColumn = 'borrow_datetime';  
let sortPendingOrder = 'desc'; 
let searchPendingQuery = "";  

function sortPendingTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    sortPendingColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortPendingOrder === 'asc') {
        sortPendingOrder = 'desc';
    } else {
        sortPendingOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updatePendingSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadPendingTransactions(currentPendingPage, rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder);
}


function updatePendingSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortPendingOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadPendingTransactions(page = 1, itemsPerPage = 5, searchPendingQuery = "", sortPendingColumn = 'borrow_id', sortPendingOrder = '') {
    currentPendingPage = page;
    rowsPendingPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_pending_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchPendingQuery)}&sortPendingColumn=${sortPendingColumn}&sortPendingOrder=${sortPendingOrder}`)
        .then(response => response.json())
        .then(data => {
            totalPendingTransaction = data.totalPendingTransaction; // Update total transactions count
            document.getElementById("pending-table-container").innerHTML = generatePendingTable(data.transactionList);
            updatePendingPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generatePendingTable(transactionList) {
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    
    const tableHeader = `
        <table id="table-pending">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortPendingTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortPendingColumn === col 
                                        ? (sortPendingOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generatePendingTableRows(transactionList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generatePendingTableRows(transactionList) {
    if (transactionList.length === 0) {
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

    return transactionList.map(transaction => {
        return `
            <tr>
                <td>${transaction.borrow_id}</td>
                <td>${transaction.borrow_datetime}</td>
                <td>${transaction.return_datetime}</td>
                <td>${transaction.title}</td>
                <td>${transaction.patrons_name}</td> 
                <td>
                    <center>
                        <div class="status ${transaction.status.toLowerCase()}">${transaction.status}</div>
                    </center>
                </td>
                <td>
                    <div class="td-center">
                        <div class="button-edit" onclick="openEditModal('${transaction.borrow_id}', '${transaction.status}')">
                            <img src="../images/edit-white.png" class="image">
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePendingPaginationControls() {
    const pagination = document.getElementById("pagination-pending");
    const entryInfo = document.getElementById("entry-info-pending");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalPendingTransaction / rowsPendingPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPendingPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPendingPage - 1) * rowsPendingPerPage + 1;
    const endEntry = Math.min(currentPendingPage * rowsPendingPerPage, totalPendingTransaction);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalPendingTransaction} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadPendingTransactions(Math.max(currentPendingPage - pageGroupSize, 1), rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder); // Include searchPendingQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPendingPage ? "active" : "";
        pageButton.onclick = () => loadPendingTransactions(i, rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder); // Include searchPendingQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadPendingTransactions(Math.min(currentPendingPage + pageGroupSize, totalPages), rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder); // Include searchPendingQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-pending").addEventListener("change", () => {
    rowsPendingPerPage = parseInt(document.getElementById("entries-pending").value);
    loadPendingTransactions(1, rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder);  // Include searchPendingQuery
});


// Initial load
loadPendingTransactions(currentPendingPage, rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder); // Default sort from DB


function searchPendingTable() {
    searchPendingQuery = document.getElementById("search-pending").value;
    loadPendingTransactions(currentPendingPage, rowsPendingPerPage, searchPendingQuery, sortPendingColumn, sortPendingOrder); // Default sort from DB
}