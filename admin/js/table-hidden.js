let currentPage = 1;
let rowsPerPage = 5;
let filteredRows = [];
let originalRows = [];
let maxPagesToShow = 5;
let debounceTimeout;


document.querySelector("#search-button").addEventListener("click", function() {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        const searchQuery = document.getElementById("search").value.trim();
        if (searchQuery) {
            fetchData(searchQuery);
        } else {
            updateTable([]); // Clear results if search is empty
        }
    }, 300); // Adjust the delay as needed
});


// Add the event listener for the clear button
document.querySelector("#clear-button").addEventListener("click", function() {
    // Clear the search input
    document.getElementById("search").value = "";

    // Reset filteredRows to originalRows
    filteredRows = originalRows.slice();

    this.style.display = "none"; // Hide the clear button

    // Update the table with the original rows
    updateTable([]);
});

document.getElementById("search").addEventListener("input", function() {
    const clearButton = document.getElementById("clear-button");
    clearButton.style.display = this.value ? "block" : "none";
});


function displayTable() {
    const table = document.getElementById("table").getElementsByTagName('tbody')[0];
    const rows = filteredRows.length ? filteredRows : Array.from(table.getElementsByTagName('tr'));
    const totalRows = rows.length;

    // Hide all rows initially
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
    const currentPageGroup = Math.floor((currentPage - 1) / maxPagesToShow);
    const startPage = currentPageGroup * maxPagesToShow + 1;
    const endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

    // Previous button for page group
    const prevGroupButton = document.createElement("button");
    prevGroupButton.textContent = "Previous";
    prevGroupButton.onclick = () => {
        if (startPage > 1) {
            currentPage = startPage - 1; // Move to the last page of the previous group
            displayTable();
        }
    };
    prevGroupButton.disabled = startPage === 1;
    pagination.appendChild(prevGroupButton);

    // Page buttons for the current group
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = currentPage === i ? "active" : "";
        pageButton.onclick = () => goToPage(i);
        pagination.appendChild(pageButton);
    }

    // Next button for page group
    const nextGroupButton = document.createElement("button");
    nextGroupButton.textContent = "Next";
    nextGroupButton.onclick = () => {
        if (endPage < totalPages) {
            currentPage = endPage + 1; // Move to the first page of the next group
            displayTable();
        }
    };
    nextGroupButton.disabled = endPage >= totalPages;
    pagination.appendChild(nextGroupButton);
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
        rows = originalRows.slice(); // Reset to original order
        document.getElementById(`sort-icon-${columnIndex}`).src = "../images/sort.png";
    } else {
        rows.sort((a, b) => {
            const cellA = a.getElementsByTagName('td')[columnIndex].textContent.trim();
            const cellB = b.getElementsByTagName('td')[columnIndex].textContent.trim();

            // Check if the content is numeric
            const isNumeric = !isNaN(cellA) && !isNaN(cellB);

            if (isNumeric) {
                // Parse to float for comparison
                const numA = parseFloat(cellA);
                const numB = parseFloat(cellB);
                if (sortDirections[columnIndex] === 1) {
                    document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png";
                    return numA - numB; // Ascending order
                } else {
                    document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png";
                    return numB - numA; // Descending order
                }
            } else {
                // Use localeCompare for string comparison
                if (sortDirections[columnIndex] === 1) {
                    document.getElementById(`sort-icon-${columnIndex}`).src = "../images/asc.png";
                    return cellA.localeCompare(cellB);
                } else {
                    document.getElementById(`sort-icon-${columnIndex}`).src = "../images/dsc.png";
                    return cellB.localeCompare(cellA);
                }
            }
        });
    }

    filteredRows = rows;
    rows.forEach(row => table.appendChild(row));
    displayTable(); // Refresh the display after sorting
}

// Call this function to initialize the table on page load
window.onload = () => {
    displayTable();
};