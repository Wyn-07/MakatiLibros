const borrowIdInput = document.getElementById('borrow_id');
const titleInput = document.getElementById('titleInput');
const bookIdInput = document.getElementById('book_id');
const patronInput = document.getElementById('patronInput');
const patronIdInput = document.getElementById('patronIdInput');

// Event listener for borrow ID input
borrowIdInput.addEventListener('input', function() {
    let input = this.value.trim();

    // Filter suggestions by borrow ID
    let suggestions = borrowingBooks.filter(book => 
        book.borrow_id.toString().includes(input)
    );

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-borrowids');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-borrowids';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.borrow_id; 
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    borrowIdInput.setAttribute('list', 'datalist-borrowids');
});

// Handle case when user makes a selection or input loses focus for borrow ID
borrowIdInput.addEventListener('blur', function() {
    const input = this.value.trim();

    const selectedBook = borrowingBooks.find(book => 
        book.borrow_id.toString() === input
    );

    if (selectedBook) {
        titleInput.value = selectedBook.title;
        bookIdInput.value = selectedBook.book_id;

        // Update patron name and ID
        patronInput.value = selectedBook.patrons_name; // Assuming patrons_name is available
        patronIdInput.value = selectedBook.patrons_id; // Assuming patrons_id is available
    } else {
        // Clear fields if input doesn't match any suggestion
        clearFields();
        // Clear the borrow ID input field
        borrowIdInput.value = '';
    }
});

// Event listener for patron name input
patronInput.addEventListener('input', function() {
    let input = this.value.trim();

    // Filter suggestions by patron name
    let suggestions = borrowingBooks.filter(book => 
        book.patrons_name.toLowerCase().includes(input.toLowerCase())
    );

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-patronnames');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-patronnames';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.patrons_name; // Assuming patrons_name is a property in borrowingBooks
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    patronInput.setAttribute('list', 'datalist-patronnames');
});

// Handle case when user makes a selection or input loses focus for patron name
patronInput.addEventListener('blur', function() {
    const input = this.value.trim();

    const selectedPatron = borrowingBooks.find(book => 
        book.patrons_name.toLowerCase() === input.toLowerCase()
    );

    if (selectedPatron) {
        patronIdInput.value = selectedPatron.patrons_id; // Update patron ID
    } else {
        // Clear fields if input doesn't match any suggestion
        patronIdInput.value = '';
        // Clear the patron input field
        patronInput.value = ''; 
    }
});

// Function to clear the fields
function clearFields() {
    titleInput.value = '';
    bookIdInput.value = '';
    patronInput.value = '';
    patronIdInput.value = '';
}
