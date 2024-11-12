let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalTransaction = 0;
let sortColumn = 'borrow_datetime';  
let sortOrder = 'desc'; 
let searchQuery = "";  

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    sortColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortOrder === 'asc') {
        sortOrder = 'desc';
    } else {
        sortOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadTransactions(currentPage, rowsPerPage, searchQuery, sortColumn, sortOrder);
}


function updateSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadTransactions(page = 1, itemsPerPage = 5, searchQuery = "", sortColumn = 'borrow_id', sortOrder = '') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_transaction_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortColumn=${sortColumn}&sortOrder=${sortOrder}`)
        .then(response => response.json())
        .then(data => {
            totalTransaction = data.totalTransaction; // Update total transactions count
            document.getElementById("transaction-table-container").innerHTML = generateTable(data.transactionList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generateTable(transactionList) {
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    
    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortColumn === col 
                                        ? (sortOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generateTableRows(transactionList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}

function generateTableRows(transactionList) {
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

function updatePaginationControls() {
    const pagination = document.getElementById("pagination");
    const entryInfo = document.getElementById("entry-info");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalTransaction / rowsPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalTransaction);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalTransaction} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadTransactions(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadTransactions(i, rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadTransactions(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    loadTransactions(1, rowsPerPage, searchQuery, sortColumn, sortOrder);  // Include searchQuery
});


// Initial load
loadTransactions(currentPage, rowsPerPage, searchQuery, sortColumn, sortOrder); // Default sort from DB


function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadTransactions(currentPage, rowsPerPage, searchQuery, sortColumn, sortOrder); // Default sort from DB
}