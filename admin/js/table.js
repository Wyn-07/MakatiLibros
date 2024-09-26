let currentPage = 1;
let rowsPerPage = 5;
let filteredRows = [];
let originalRows = [];
let maxPagesToShow = 5;



function displayTable() {
    const table = document.getElementById("table").getElementsByTagName('tbody')[0];
    const rows = filteredRows.length ? filteredRows : Array.from(table.getElementsByTagName('tr'));
    const totalRows = rows.length;

    for (let i = 0; i < table.getElementsByTagName('tr').length; i++) {
        table.getElementsByTagName('tr')[i].style.display = "none";
    }

    let start = (currentPage - 1) * rowsPerPage;
    let end = start + rowsPerPage;

    for (let i = start; i < end && i < totalRows; i++) {
        rows[i].style.display = "";
    }

    updatePagination(totalRows);
    updateEntryInfo(totalRows);
}

function updatePagination(totalRows) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    const totalPages = Math.ceil(totalRows / rowsPerPage);
    const currentPageGroup = Math.floor((currentPage - 1) / maxPagesToShow); // Group of pages (0 for pages 1-5, 1 for 6-10, etc.)
    const startPage = currentPageGroup * maxPagesToShow + 1; // Start of the page group
    const endPage = Math.min(startPage + maxPagesToShow - 1, totalPages); // End of the page group

    // Previous button
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.onclick = () => {
        if (startPage > 1) { // Go to previous set of pages
            currentPage = startPage - 1;
            displayTable();
        }
    };
    prevButton.disabled = startPage === 1; // Disable if you're on the first group
    pagination.appendChild(prevButton);

    // Page buttons for the current group
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = currentPage === i ? "active" : "";
        pageButton.onclick = () => goToPage(i);
        pagination.appendChild(pageButton);
    }

    // Next button
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.onclick = () => {
        if (endPage < totalPages) { // Go to the next set of pages
            currentPage = endPage + 1;
            displayTable();
        }
    };
    nextButton.disabled = endPage === totalPages; // Disable if you're on the last group
    pagination.appendChild(nextButton);
}



function updateEntryInfo(totalRows) {
    const entryInfo = document.getElementById("entry-info");
    const start = (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, totalRows);
    entryInfo.textContent = `Showing ${start} to ${end} of ${totalRows} entries`;
}



function goToPage(page) {
    currentPage = page;
    displayTable();
}

function changeEntries() {
    rowsPerPage = parseInt(document.getElementById("entries").value);
    currentPage = 1;
    displayTable();
}


function searchTable() {
    const input = document.getElementById("search").value.toLowerCase();
    const table = document.getElementById("table").getElementsByTagName('tbody')[0];
    
    // Get the original rows if not already stored
    if (originalRows.length === 0) {
        originalRows = Array.from(table.getElementsByTagName('tr'));
    }

    // If the search field is empty, reset to the original rows
    if (input === "") {
        filteredRows = originalRows; // Reset to original rows
    } else {
        // Filter rows based on search input
        filteredRows = originalRows.filter(row => {
            const cells = Array.from(row.getElementsByTagName('td'));
            return cells.some(cell => cell.textContent.toLowerCase().includes(input));
        });
    }

    // Clear the table before rendering new rows
    table.innerHTML = "";

    if (filteredRows.length === 0) {
        // No results found, display a message
        const noResultRow = document.createElement('tr');
        const noResultCell = document.createElement('td');
        
        // Use the colspan constant
        noResultCell.setAttribute('colspan', NO_RESULT_COLSPAN);
        noResultCell.innerHTML = `
            <div class="no-result">
                <div class="no-result-image">
                    <img src="../images/no-result.jpg" alt="No Results Found" class="image"/>
                </div>
                <p>No results found.</p>
            </div>`;
        
        noResultRow.appendChild(noResultCell);
        table.appendChild(noResultRow);
    } else {
        // Display the filtered or original rows
        filteredRows.forEach(row => table.appendChild(row));
    }

    // Reset pagination after search
    currentPage = 1;
    displayTable();
}



function sortTable(columnIndex) {
    const table = document.getElementById("table").getElementsByTagName('tbody')[0];
    let rows = filteredRows.length ? filteredRows : Array.from(table.getElementsByTagName('tr'));

    if (originalRows.length === 0) {
        originalRows = Array.from(rows);
    }

    sortDirections[columnIndex] = (sortDirections[columnIndex] + 1) % 3;

    // Reset icons for all columns except the current one
    for (let i = 0; i < sortDirections.length; i++) {
        if (i !== columnIndex) {
            sortDirections[i] = 0;
            document.getElementById(`sort-icon-${i}`).src = "../images/sort.png";
        }
    }

    if (sortDirections[columnIndex] === 0) {
        rows = originalRows;
        document.getElementById(`sort-icon-${columnIndex}`).src = "../images/sort.png";
    } else {
        rows.sort((a, b) => {
            const cellA = a.getElementsByTagName('td')[columnIndex].textContent;
            const cellB = b.getElementsByTagName('td')[columnIndex].textContent;

            if (sortDirections[columnIndex] === 1) {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png";
                return cellA.localeCompare(cellB);
            } else {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png";
                return cellB.localeCompare(cellA);
            }
        });
    }

    if (sortDirections[columnIndex] === 0) {
        rows = originalRows.slice(); // Reset to original order
    }
    
    filteredRows = rows;
    rows.forEach(row => table.appendChild(row));
    displayTable();
}

window.onload = displayTable;

