
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
        noResultCell.setAttribute('colspan', 5); // Assuming 5 columns
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

