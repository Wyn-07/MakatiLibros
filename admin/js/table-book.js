let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalBooks = 0;
let sortBookColumn = 'title';
let sortBookOrder = 'desc';
let searchQuery = "";

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['acc_number', 'class_number', 'title', 'author_name', 'category_name', 'copyright'];
    sortBookColumn = columnNames[columnIndex];

    // Toggle the sort order based on current order
    if (sortBookOrder === 'asc') {
        sortBookOrder = 'desc';
    } else {
        sortBookOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateSortIcon(columnIndex);

    // Reload the books with the new sorting parameters
    loadBooks(currentPage, rowsPerPage, searchQuery, sortBookColumn, sortBookOrder);
}

function updateSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);

    if (sortBookOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";
    } else {
        currentSortIcon.src = "../images/dsc.png";
    }
}

function loadBooks(page = 1, itemsPerPage = 5, searchQuery = "", sortBookColumn = 'title', sortBookOrder = '') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    // Fetch books with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_book_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortBookColumn=${sortBookColumn}&sortBookOrder=${sortBookOrder}`)
        .then(response => response.json())
        .then(data => {
            totalBooks = data.totalBooks; // Update total books count
            document.getElementById("book-table-container").innerHTML = generateTable(data.bookList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching books:", error));
}

function generateTable(bookList) {
    const columnNames = ['acc_number', 'class_number', 'title', 'author_name', 'category_name', 'copyright'];

    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${sortBookColumn === col
            ? (sortBookOrder === 'asc' ? 'asc.png' : 'dsc.png')
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
                ${generateTableRows(bookList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}

function generateTableRows(bookList) {
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

    return bookList.map(book => {
        return `
            <tr>
                <td>${book.acc_number}</td>
                <td>${book.class_number}</td>
                <td>${book.title}</td>
                <td>${book.author_name}</td>
                <td>${book.category_name}</td>
                <td>${book.copyright}</td>
                <td>
                    <div class="td-center">
                        <div class="button-edit" 
                            data-book-id="${encodeURIComponent(book.book_id)}"
                            data-acc-number="${encodeURIComponent(book.acc_number)}"
                            data-class-number="${encodeURIComponent(book.class_number)}"
                            data-title="${encodeURIComponent(book.title)}"
                            data-author-name="${encodeURIComponent(book.author_name)}"
                            data-author-id="${encodeURIComponent(book.author_id)}"
                            data-category-name="${encodeURIComponent(book.category_name)}"
                            data-category-id="${encodeURIComponent(book.category_id)}"
                            data-copyright="${encodeURIComponent(book.copyright)}"
                            data-image="${encodeURIComponent(book.image)}"
                            onclick="openEditModal(this)">
                            <img src="../images/edit-white.png" class="image">
                        </div>

                        <div class="button-delete" 
                            data-delete-book-id="${encodeURIComponent(book.book_id)}"
                            onclick="openDeleteModal(this)">
                            <img src="../images/delete-white.png" class="image">
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

    const totalPages = Math.ceil(totalBooks / rowsPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalBooks);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalBooks} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadBooks(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortBookColumn, sortBookOrder); // Include searchQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadBooks(i, rowsPerPage, searchQuery, sortBookColumn, sortBookOrder); // Include searchQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadBooks(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortBookColumn, sortBookOrder); // Include searchQuery
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    loadBooks(1, rowsPerPage, searchQuery, sortBookColumn, sortBookOrder);  // Include searchQuery
});

// Initial load
loadBooks(currentPage, rowsPerPage, searchQuery, sortBookColumn, sortBookOrder); // Default sort from DB

function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadBooks(currentPage, rowsPerPage, searchQuery, sortBookColumn, sortBookOrder); // Default sort from DB
}
