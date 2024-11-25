let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("entries").value);
let totalAuthors = 0;
let sortColumn = 'author';
let sortOrder = 'asc';
let searchQuery = "";  // Keep track of the search query globally

function sortTable(columnIndex) {
    // Determine which column to sort by
    if (columnIndex === 0) {
        sortColumn = 'author';
    }

    // Toggle sort order and update the icon
    if (sortOrder === 'asc') {
        sortOrder = 'desc';
        document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png";
    } else if (sortOrder === 'desc') {
        sortOrder = 'asc';
        document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png";
    }

    // Reload authors with the new sorting parameters
    loadAuthors(currentPage, rowsPerPage, searchQuery, sortColumn, sortOrder);
}

// Update the loadAuthors function to include sortColumn and sortOrder
function loadAuthors(page = 1, itemsPerPage = 5, searchQuery = "", sortColumn = 'author', sortOrder = 'asc') {
    currentPage = page;
    rowsPerPage = itemsPerPage;

    // Fetch authors with pagination parameters, search query, and sorting info
    fetch(`functions/fetch_author_pagination.php?page=${page}&itemsPerPage=${itemsPerPage}&search=${encodeURIComponent(searchQuery)}&sortColumn=${sortColumn}&sortOrder=${sortOrder}`)
        .then(response => response.json())
        .then(data => {
            totalAuthors = data.totalAuthors; // Update total authors count
            document.getElementById("author-table-container").innerHTML = generateTable(data.authorList, sortColumn, sortOrder);
            updatePaginationControls();
        })
        .catch(error => console.error("Error fetching authors:", error));
}


function generateTable(authorList, sortColumn, sortOrder) {
    const tableHeader = `
        <table id="table">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">
                        <div class="row row-between">
                            <div class="column-title">Author Name</div>
                            <img id="sort-icon-0" src="../images/${sortOrder === 'asc' ? 'asc' : 'dsc'}.png" class="sort">
                        </div>
                    </th>
                    <th>
                        <div class="column-title">Edit Author Name</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                ${generateTableRows(authorList)}
            </tbody>
        </table>
    `;
    return tableHeader;
}


function generateTableRows(authorList) {
    if (authorList.length === 0) {
        return `
            <tr>
                <td colspan="2">
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

    return authorList.map(author => {
        // URI encode the author name
        const encodedAuthorName = encodeURIComponent(author.author);
    
        return `
            <tr>
                <td>${author.author}</td>
                <td>
                    <div class="td-center">
                        <div class="button-edit" 
                             data-author-id="${author.author_id}" 
                             data-author-name="${encodedAuthorName}" 
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

    const totalPages = Math.ceil(totalAuthors / rowsPerPage);
    const pageGroupSize = 5; // Number of pages to show at a time
    const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
    const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

    // Update entry info text (showing current range of items)
    const startEntry = (currentPage - 1) * rowsPerPage + 1;
    const endEntry = Math.min(currentPage * rowsPerPage, totalAuthors);
    entryInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalAuthors} entries`;

    // "Previous" button (to show the previous group of pages)
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.disabled = startPage === 1; // Disable if we are on the first page group
    prevButton.onclick = () => {
        if (!prevButton.disabled) {
            loadAuthors(Math.max(currentPage - pageGroupSize, 1), rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        }
    };
    pagination.appendChild(prevButton);

    // Page buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? "active" : "";
        pageButton.onclick = () => loadAuthors(i, rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        pagination.appendChild(pageButton);
    }

    // "Next" button (to show the next group of pages)
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.disabled = endPage === totalPages; // Disable if we are on the last page group
    nextButton.onclick = () => {
        if (!nextButton.disabled) {
            loadAuthors(Math.min(currentPage + pageGroupSize, totalPages), rowsPerPage, searchQuery, sortColumn, sortOrder); // Include searchQuery
        }
    };
    pagination.appendChild(nextButton);
}


document.getElementById("entries").addEventListener("change", () => {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    // Reload authors with the current page, search query, and new rowsPerPage
    loadAuthors(currentPage, rowsPerPage, searchQuery, sortColumn, sortOrder);
});

document.addEventListener("DOMContentLoaded", () => loadAuthors(currentPage, rowsPerPage, searchQuery));

function searchTable() {
    searchQuery = document.getElementById("search").value;
    loadAuthors(1, rowsPerPage, searchQuery); // Fetch the results with the search term
}


