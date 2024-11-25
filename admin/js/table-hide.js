let currentPage = 1;
let rowsPerPage = 5;
let filteredRows = [];
let originalRows = [];
let maxPagesToShow = 5;

function displayTable() {
    const tableBody = document.getElementById("tableBody");
    const rows = filteredRows.length ? filteredRows : originalRows;
    const totalRows = rows.length;

    // Hide all rows initially
    for (let row of originalRows) {
        row.style.display = "none";
    }

    // Show rows for the current page
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
    const currentPageGroup = Math.floor((currentPage - 1) / maxPagesToShow);
    const startPage = currentPageGroup * maxPagesToShow + 1;
    const endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

    // Previous button
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.onclick = () => {
        if (startPage > 1) {
            currentPage = startPage - 1;
            displayTable();
        }
    };
    prevButton.disabled = startPage === 1;
    pagination.appendChild(prevButton);

    // Page buttons
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
        if (endPage < totalPages) {
            currentPage = endPage + 1;
            displayTable();
        }
    };
    nextButton.disabled = endPage === totalPages;
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
    const tableBody = document.getElementById("tableBody");
    const tableSearch = document.getElementById("tableSearch");
    const tableNone = document.getElementById("tableNone");
    const entryInfoRow = document.getElementById("entry-pagination-row");

    // Populate original rows if not already done
    if (originalRows.length === 0) {
        originalRows = Array.from(tableBody.getElementsByTagName('tr'));
    }

    // Reset if search is empty
    if (input === "") {
        filteredRows = [];
        tableBody.style.display = "none"; // Hide the main table
        tableSearch.style.display = "table-row-group"; // Show the search table
        tableNone.style.display = "none"; // Hide the main table
        entryInfoRow.style.display = "none"; // Hide entry info
        return;
    }

    // Filter rows based on search input
    filteredRows = originalRows.filter(row => {
        const cells = Array.from(row.getElementsByTagName('td'));
        return cells.some(cell => cell.textContent.toLowerCase().includes(input));
    });

    if (filteredRows.length > 0) {
        // Show filtered results
        tableBody.style.display = "table-row-group"; // Show the main table
        tableSearch.style.display = "none"; // Hide the search table
        tableNone.style.display = "none"; // Hide the search table
        entryInfoRow.style.display = ""; // Show entry info
    } else {
        // No results - Show "no results" section
        tableBody.style.display = "none"; // Hide the main table
        tableSearch.style.display = "none"; // Show the search table
        tableNone.style.display = "table-row-group"; // Show the search table
        entryInfoRow.style.display = "none"; // Hide entry info
    }

    // Reset to first page and display filtered rows
    currentPage = 1;
    displayTable();
}


function sortTable(columnIndex) {
    const tableBody = document.getElementById("tableBody");
    let rows = filteredRows.length ? filteredRows : Array.from(tableBody.getElementsByTagName('tr'));

    // Initialize sort directions if not already done
    if (sortDirections.length === 0) {
        sortDirections = Array(rows[0].cells.length).fill(0);
    }

    // Toggle sort direction
    sortDirections[columnIndex] = (sortDirections[columnIndex] + 1) % 3;

    // Reset icons for other columns
    for (let i = 0; i < sortDirections.length; i++) {
        if (i !== columnIndex) {
            sortDirections[i] = 0;
            document.getElementById(`sort-icon-${i}`).src = "../images/sort.png"; // Default icon for other columns
        }
    }

    // Update the icon for the current column based on sort direction
    if (sortDirections[columnIndex] === 0) {
        rows = originalRows.slice(); // Reset to original order
        document.getElementById(`sort-icon-${columnIndex}`).src = "../images/sort.png"; // Reset icon
    } else {
        rows.sort((a, b) => {
            const cellA = a.getElementsByTagName('td')[columnIndex].textContent.trim();
            const cellB = b.getElementsByTagName('td')[columnIndex].textContent.trim();

            // Convert to numbers if possible, else keep as strings
            const valA = isNaN(cellA) ? cellA : parseFloat(cellA);
            const valB = isNaN(cellB) ? cellB : parseFloat(cellB);

            // Compare values based on the current sort direction
            if (sortDirections[columnIndex] === 1) {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png"; // Ascending icon
                return valA > valB ? 1 : -1; // Ascending
            } else {
                document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png"; // Descending icon
                return valA < valB ? 1 : -1; // Descending
            }
        });
    }

    // Clear the table body and append sorted rows
    tableBody.innerHTML = ""; // Clear existing rows
    rows.forEach(row => tableBody.appendChild(row));

    // Update filteredRows to the newly sorted rows
    filteredRows = rows;

    // Display the table
    displayTable();
}



window.onload = () => {
    // Set up originalRows on load for initial display
    const tableBody = document.getElementById("tableBody");
    originalRows = Array.from(tableBody.getElementsByTagName('tr'));
    displayTable();
};
