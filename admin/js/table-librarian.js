let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalLibrarians = 0;
let sortLibrarianColumn = 'librarian_id';
let sortLibrarianOrder = 'desc';
let searchQuery = "";

function sortTable(columnIndex) {
    // Map column index to column name for sorting
    const columnNames = ['librarian_name', 'contact', 'email'];
    sortLibrarianColumn = columnNames[columnIndex];

    // Toggle the sort order based on current order
    if (sortLibrarianOrder === 'asc') {
        sortLibrarianOrder = 'desc';
    } else {
        sortLibrarianOrder = 'asc';
    }

    // Only update the icon for the clicked column
    updateSortIcon(columnIndex);

    // Reload the librarians with the new sorting parameters
    loadLibrarians(currentPage, rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
}

function updateSortIcon(columnIndex) {
    // Reset only the icon for the clicked column based on the current sort order
    const currentSortIcon = document.getElementById(`sort-icon-${columnIndex}`);

    if (sortLibrarianOrder === 'asc') {
        currentSortIcon.src = "../images/asc.png";
    } else {
        currentSortIcon.src = "../images/dsc.png";
    }
}

function loadLibrarians(page = 1, itemsPerPage = 5, searchQuery = "", sortLibrarianColumn = 'librarian_name', sortLibrarianOrder = '') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    // Fetch librarians with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_librarian_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortLibrarianColumn=${sortLibrarianColumn}&sortLibrarianOrder=${sortLibrarianOrder}`)
        .then(response => response.json())
        .then(data => {
            totalLibrarians = data.totalLibrarians; // Update total librarians count
            document.getElementById("librarian-table-container").innerHTML = generateTable(data.librarianList);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching librarians:", error));
}

function generateTable(librarianList) {
    const columnNames = ['librarian_name', 'contact', 'email'];

    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    ${columnNames.map((col, index) => `
                        <th onclick="sortTable(${index})">
                            <div class="row row-between">
                                <div class="column-title">${col.replace('_', ' ').toUpperCase()}</div>
                                <img id="sort-icon-${index}" src="../images/${sortLibrarianColumn === col
                                    ? (sortLibrarianOrder === 'asc' ? 'asc.png' : 'dsc.png')
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
                ${generateTableRows(librarianList)}
            </tbody>
        </table>
    `;

    return tableHeader;
}

function generateTableRows(librarianList) {
    if (librarianList.length === 0) {
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

    return librarianList.map(librarian => {
        return `
            <tr>
                <td>${librarian.librarian_name}</td>
                <td>${librarian.librarian_contact}</td>
                <td>${librarian.librarian_email}</td>
                <td>
                    <div class="td-center">
                        <div class="button-edit" 
                            data-librarian-id="${encodeURIComponent(librarian.librarians_id)}"
                            data-librarian-firstname="${encodeURIComponent(librarian.librarian_firstname)}"
                            data-librarian-middlename="${encodeURIComponent(librarian.librarian_middlename)}"
                            data-librarian-lastname="${encodeURIComponent(librarian.librarian_lastname)}"
                            data-librarian-suffix="${encodeURIComponent(librarian.librarian_suffix)}"
                            data-librarian-birthdate="${encodeURIComponent(librarian.librarian_birthdate)}"
                            data-librarian-age="${encodeURIComponent(librarian.librarian_age)}"
                            data-librarian-gender="${encodeURIComponent(librarian.librarian_gender)}"
                            data-librarian-contact="${encodeURIComponent(librarian.librarian_contact)}"
                            data-librarian-address="${encodeURIComponent(librarian.librarian_address)}"
                            data-librarian-email="${encodeURIComponent(librarian.librarian_email)}"
                            data-librarian-image="${encodeURIComponent(librarian.librarian_image)}"
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
    const entryInfo = document.getElementById("entry-info");
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalLibrarians / rowsPerPage);
    const pageGroupSize = 5;
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalLibrarians);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalLibrarians} entries`;

    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1;
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadLibrarians(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
        }
    };
    pagination.appendChild(prevButton);

    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadLibrarians(i, rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
        pagination.appendChild(pageButton);
    }

    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages;
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadLibrarians(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
        }
    };
    pagination.appendChild(nextButton);
}

document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    loadLibrarians(currentPage, rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
});

// Initial load
loadLibrarians(currentPage, rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);

function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadLibrarians(currentPage, rowsPerPage, searchQuery, sortLibrarianColumn, sortLibrarianOrder);
}
