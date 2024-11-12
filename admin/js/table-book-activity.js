let currentBooksPage = 1;
let rowsBooksPerPage = parseInt(document.getElementById("entries-books").value);
let totalBooksLogs = 0;
let sortBooksColumn = 'date_time';  
let sortBooksOrder = 'desc'; 
let searchBooksQuery = "";  

function sortBooksTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['date_time', 'page', 'manage', 'librarian_name'];
    sortBooksColumn = columnNames[columnIndex];  // Update column name for sorting

    // Toggle the sort order based on current order
    if (sortBooksOrder === 'asc') {
        sortBooksOrder = 'desc';
    } else {
        sortBooksOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateBooksSortIcon(columnIndex);

    // Reload the transactions with the new sorting parameters
    loadBooks(currentBooksPage, rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder);
}


function updateBooksSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);
    
    if (sortBooksOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";  // Ascending icon
    } else {
        currentSortIcon.src = "../images/dsc.png";  // Descending icon
    }
}

function loadBooks(page = 1, itemsPerPage = 5, searchBooksQuery = "", sortBooksColumn = 'date_time', sortBooksOrder = '') {
    currentBooksPage = page;
    rowsBooksPerPage = itemsPerPage;

    // Fetch transactions with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_book_activity_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchBooksQuery)}&sortBooksColumn=${sortBooksColumn}&sortBooksOrder=${sortBooksOrder}`)
        .then(response => response.json())
        .then(data => {
            totalBooksLogs = data.totalBooksLogs; // Update total transactions count
            document.getElementById("book-table-container").innerHTML = generateBooksTable(data.bookList);
            updateBooksPaginationControls();
        })
        .catch(error => console.error("Error fetching transactions:", error));
}


function generateBooksTable(bookList) {
    const columnNames = ['date_time', 'page', 'manage', 'librarian_name'];
    
    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortBooksTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${
                                    sortBooksColumn === col 
                                        ? (sortBooksOrder === 'asc' ? 'asc.png' : 'dsc.png') 
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
                ${generateBooksTableRows(bookList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}

function generateBooksTableRows(bookList) {
    if (bookList.length === 0) {
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

    return bookList.map(log => {
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

function updateBooksPaginationControls() {
    const pagination = document.getElementById("pagination");
    const entryInfo = document.getElementById("entry-info");  // Get the entry info element
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalBooksLogs / rowsBooksPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentBooksPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentBooksPage - 1) * rowsBooksPerPage + 1;
    const endEntry = Math.min(currentBooksPage * rowsBooksPerPage, totalBooksLogs);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalBooksLogs} entries-books`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadBooks(Math.max(currentBooksPage - pageGroupSize, 1), rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder); // Include searchBooksQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentBooksPage ? "active" : "";
        pageButton.onclick = () => loadBooks(i, rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder); // Include searchBooksQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadBooks(Math.min(currentBooksPage + pageGroupSize, totalPages), rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder); // Include searchBooksQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries-books").addEventListener("change", () => {
    rowsBooksPerPage = parseInt(document.getElementById("entries-books").value);
    loadBooks(1, rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder);  // Include searchBooksQuery
});


// Initial load
loadBooks(currentBooksPage, rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder); // Default sort from DB


function searchBooksTable() {
    searchBooksQuery = document.getElementById("search-books").value;
    loadBooks(currentBooksPage, rowsBooksPerPage, searchBooksQuery, sortBooksColumn, sortBooksOrder); // Default sort from DB
}