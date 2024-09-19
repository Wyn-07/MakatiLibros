let currentPage = 1;
let rowsPerPage = 5;
let sortDirections = [0, 0, 0];
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
    const startPage = Math.floor((currentPage - 1) / maxPagesToShow) * maxPagesToShow + 1;
    const endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.onclick = prevPage;
    prevButton.disabled = currentPage === 1;
    pagination.appendChild(prevButton);

    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.className = currentPage === i ? "active" : "";
        pageButton.onclick = () => goToPage(i);
        pagination.appendChild(pageButton);
    }

    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.onclick = nextPage;
    nextButton.disabled = currentPage === totalPages;
    pagination.appendChild(nextButton);
}

function updateEntryInfo(totalRows) {
    const entryInfo = document.getElementById("entry-info");
    const start = (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, totalRows);
    entryInfo.textContent = `Showing ${start} to ${end} of ${totalRows} entries`;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayTable();
    }
}

function nextPage() {
    const table = document.getElementById("table").getElementsByTagName('tbody')[0];
    const totalRows = filteredRows.length ? filteredRows.length : table.getElementsByTagName('tr').length;

    if ((currentPage * rowsPerPage) < totalRows) {
        currentPage++;
        displayTable();
    }
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
    const rows = Array.from(table.getElementsByTagName('tr'));

    filteredRows = rows.filter(row => {
        const cells = Array.from(row.getElementsByTagName('td'));
        return cells.some(cell => cell.textContent.toLowerCase().includes(input));
    });

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