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
            let cellA = a.getElementsByTagName('td')[columnIndex].textContent.trim();
            let cellB = b.getElementsByTagName('td')[columnIndex].textContent.trim();

            // Determine if the column is a date or number column
            if (isDateColumn(columnIndex)) {
                cellA = parseDate(cellA);
                cellB = parseDate(cellB);
            } else if (isNumericColumn(columnIndex)) {
                cellA = parseInt(cellA, 10);
                cellB = parseInt(cellB, 10);
            }

            if (sortDirections[columnIndex] === 1) {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png";
                return cellA > cellB ? 1 : -1;
            } else {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png";
                return cellA < cellB ? 1 : -1;
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

// Helper function to check if a column contains dates
function isDateColumn(columnIndex) {
    return columnIndex === 0; // Adjust this index to match your date column
}

// Helper function to check if a column contains numbers
function isNumericColumn(columnIndex) {
    return columnIndex === 1; // Adjust this index to match your numeric column
}

// Helper function to parse a date string in MM/DD/YYYY format to a Date object
function parseDate(dateString) {
    const [month, day, year] = dateString.split('/').map(Number);
    return new Date(year, month - 1, day); // month is 0-based in Date constructor
}



window.onload = displayTable;

