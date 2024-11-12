let currentBorrowedPage = 1;
let rowsBorrowedPerPage = parseInt(document.getElementById("entries").value);
let totalBorrowedTransaction = 0;
let sortBorrowedColumn = 'borrow_datetime';  
let sortBorrowedOrder = 'desc'; 
let searchBorrowedQuery = "";  

function sortBorrowedTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    sortBorrowedColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortBorrowedOrder === 'asc') {
        sortBorrowedOrder = 'desc';
    } else {
        sortBorrowedOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateBorrowedIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadBorrowedTransactions(currentBorrowedPage, rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder);
}


function updateBorrowedIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortBorrowedOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadBorrowedTransactions(page = 1, itemsPerPage = 5, searchBorrowedQuery = "", sortBorrowedColumn = 'borrow_id', sortBorrowedOrder = '') {
    currentBorrowedPage = page;
    rowsBorrowedPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_borrowed_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchBorrowedQuery)}&sortBorrowedColumn=${sortBorrowedColumn}&sortBorrowedOrder=${sortBorrowedOrder}`)
        .then(response => response.json())
        .then(data => {
            totalBorrowedTransaction = data.totalBorrowedTransaction; // Update total transactions count
            document.getElementById("borrowed-table-container").innerHTML = generateBorrowedTable(data.transactionList);
            updateBorrowedPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generateBorrowedTable(transactionList) {
    const columnNames = ['borrow_id', 'borrow_datetime', 'return_datetime', 'title', 'patrons_name', 'status'];
    
    const tableHeader = `
        <table id="table-borrowed">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortBorrowedTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortBorrowedColumn === col 
                                        ? (sortBorrowedOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generateBorrowedTableRows(transactionList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generateBorrowedTableRows(transactionList) {
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

function updateBorrowedPaginationControls() {
    const pagination = document.getElementById("pagination-borrowed");
    const entryInfo = document.getElementById("entry-info-borrowed");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalBorrowedTransaction / rowsBorrowedPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentBorrowedPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentBorrowedPage - 1) * rowsBorrowedPerPage + 1;
    const endEntry = Math.min(currentBorrowedPage * rowsBorrowedPerPage, totalBorrowedTransaction);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalBorrowedTransaction} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadBorrowedTransactions(Math.max(currentBorrowedPage - pageGroupSize, 1), rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder); // Include searchBorrowedQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentBorrowedPage ? "active" : "";
        pageButton.onclick = () => loadBorrowedTransactions(i, rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder); // Include searchBorrowedQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadBorrowedTransactions(Math.min(currentBorrowedPage + pageGroupSize, totalPages), rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder); // Include searchBorrowedQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-borrowed").addEventListener("change", () => {
    rowsBorrowedPerPage = parseInt(document.getElementById("entries-borrowed").value);
    loadBorrowedTransactions(1, rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder);  // Include searchBorrowedQuery
});


// Initial load
loadBorrowedTransactions(currentBorrowedPage, rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder); // Default sort from DB


function searchBorrowedTable() {
    searchBorrowedQuery = document.getElementById("search-borrowed").value;
    loadBorrowedTransactions(currentBorrowedPage, rowsBorrowedPerPage, searchBorrowedQuery, sortBorrowedColumn, sortBorrowedOrder); // Default sort from DB
}




