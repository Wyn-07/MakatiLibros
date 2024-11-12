let currentReturnedPage = 1;
let rowsReturnedPerPage = parseInt(document.getElementById("entries").value);
let totalReturnedTransaction = 0;
let sortReturnedColumn = 'borrow_datetime';  
let sortReturnedOrder = 'desc'; 
let searchReturnedQuery = "";  

function sortreturnedTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    sortReturnedColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortReturnedOrder === 'asc') {
        sortReturnedOrder = 'desc';
    } else {
        sortReturnedOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateReturnedSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadReturnedTransactions(currentReturnedPage, rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder);
}


function updateReturnedSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortReturnedOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadReturnedTransactions(page = 1, itemsPerPage = 5, searchReturnedQuery = "", sortReturnedColumn = 'borrow_id', sortReturnedOrder = '') {
    currentReturnedPage = page;
    rowsReturnedPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_returned_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchReturnedQuery)}&sortReturnedColumn=${sortReturnedColumn}&sortReturnedOrder=${sortReturnedOrder}`)
        .then(response => response.json())
        .then(data => {
            totalReturnedTransaction = data.totalReturnedTransaction; // Update total transactions count
            document.getElementById("returned-table-container").innerHTML = generateReturnedTable(data.transactionList);
            updateReturnedPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generateReturnedTable(transactionList) {
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    
    const tableHeader = `
        <table id="table-returned">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortreturnedTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortReturnedColumn === col 
                                        ? (sortReturnedOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generateReturnedTableRows(transactionList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generateReturnedTableRows(transactionList) {
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

function updateReturnedPaginationControls() {
    const pagination = document.getElementById("pagination-returned");
    const entryInfo = document.getElementById("entry-info-returned");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalReturnedTransaction / rowsReturnedPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentReturnedPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentReturnedPage - 1) * rowsReturnedPerPage + 1;
    const endEntry = Math.min(currentReturnedPage * rowsReturnedPerPage, totalReturnedTransaction);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalReturnedTransaction} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadReturnedTransactions(Math.max(currentReturnedPage - pageGroupSize, 1), rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder); // Include searchReturnedQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentReturnedPage ? "active" : "";
        pageButton.onclick = () => loadReturnedTransactions(i, rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder); // Include searchReturnedQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadReturnedTransactions(Math.min(currentReturnedPage + pageGroupSize, totalPages), rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder); // Include searchReturnedQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-returned").addEventListener("change", () => {
    rowsReturnedPerPage = parseInt(document.getElementById("entries-returned").value);
    loadReturnedTransactions(1, rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder);  // Include searchReturnedQuery
});


// Initial load
loadReturnedTransactions(currentReturnedPage, rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder); // Default sort from DB


function searchReturnedTable() {
    searchReturnedQuery = document.getElementById("search-returned").value;
    loadReturnedTransactions(currentReturnedPage, rowsReturnedPerPage, searchReturnedQuery, sortReturnedColumn, sortReturnedOrder); // Default sort from DB
}