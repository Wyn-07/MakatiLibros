let currentAcceptedPage = 1;
let rowsAcceptedPerPage = parseInt(document.getElementById("entries").value);
let totalAcceptedTransaction = 0;
let sortAcceptedColumn = 'accepted_datetime';  
let sortAcceptedOrder = 'desc'; 
let searchAcceptedQuery = "";  

function sortAcceptedTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['borrow_id', 'accepted_datetime', 'borrow_datetime', 'title', 'patrons_name', 'status'];
    sortAcceptedColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortAcceptedOrder === 'asc') {
        sortAcceptedOrder = 'desc';
    } else {
        sortAcceptedOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateAcceptedIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadAcceptedTransactions(currentAcceptedPage, rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder);
}


function updateAcceptedIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortAcceptedOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadAcceptedTransactions(page = 1, itemsPerPage = 5, searchAcceptedQuery = "", sortAcceptedColumn = 'borrow_id', sortAcceptedOrder = '') {
    currentAcceptedPage = page;
    rowsAcceptedPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_accepted_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchAcceptedQuery)}&sortAcceptedColumn=${sortAcceptedColumn}&sortAcceptedOrder=${sortAcceptedOrder}`)
        .then(response => response.json())
        .then(data => {
            totalAcceptedTransaction = data.totalAcceptedTransaction; // Update total transactions count
            document.getElementById("accepted-table-container").innerHTML = generateAcceptedTable(data.transactionList);
            updateAcceptedPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generateAcceptedTable(transactionList) {
    const columnNames = ['borrow_id', 'accepted_datetime', 'borrow_datetime', 'title', 'patrons_name', 'status'];
    
    const tableHeader = `
        <table id="table-accepted">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortAcceptedTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortAcceptedColumn === col 
                                        ? (sortAcceptedOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generateAcceptedTableRows(transactionList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generateAcceptedTableRows(transactionList) {
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
                <td>${transaction.accepted_datetime}</td>
                <td>${transaction.borrow_datetime}</td>
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

function updateAcceptedPaginationControls() {
    const pagination = document.getElementById("pagination-accepted");
    const entryInfo = document.getElementById("entry-info-accepted");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalAcceptedTransaction / rowsAcceptedPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentAcceptedPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentAcceptedPage - 1) * rowsAcceptedPerPage + 1;
    const endEntry = Math.min(currentAcceptedPage * rowsAcceptedPerPage, totalAcceptedTransaction);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalAcceptedTransaction} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadAcceptedTransactions(Math.max(currentAcceptedPage - pageGroupSize, 1), rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder); // Include searchAcceptedQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentAcceptedPage ? "active" : "";
        pageButton.onclick = () => loadAcceptedTransactions(i, rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder); // Include searchAcceptedQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadAcceptedTransactions(Math.min(currentAcceptedPage + pageGroupSize, totalPages), rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder); // Include searchAcceptedQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-accepted").addEventListener("change", () => {
    rowsAcceptedPerPage = parseInt(document.getElementById("entries-accepted").value);
    loadAcceptedTransactions(1, rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder);  // Include searchAcceptedQuery
});


// Initial load
loadAcceptedTransactions(currentAcceptedPage, rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder); // Default sort from DB


function searchAcceptedTable() {
    searchAcceptedQuery = document.getElementById("search-accepted").value;
    loadAcceptedTransactions(currentAcceptedPage, rowsAcceptedPerPage, searchAcceptedQuery, sortAcceptedColumn, sortAcceptedOrder); // Default sort from DB
}




