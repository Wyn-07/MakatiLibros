const borrowIdInput = document.getElementById('borrow_id');
const accNumInput = document.getElementById('acc_num');
const classNumInput = document.getElementById('class_num');
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

    // Check for an exact match after a short delay (allows user time to select from datalist)
    setTimeout(() => {
        const selectedBook = borrowingBooks.find(book => 
            book.borrow_id.toString() === input
        );

        if (selectedBook) {
            // Populate fields if there's an exact match
            classNumInput.value = selectedBook.class_number;
            accNumInput.value = selectedBook.acc_number;
            titleInput.value = selectedBook.title;
            bookIdInput.value = selectedBook.book_id;
            patronInput.value = selectedBook.patrons_name;
            patronIdInput.value = selectedBook.patrons_id;
        } else {
            // Clear all fields if no exact match is found
            borrowIdInput.value = '';  // Clear the borrow_id input
            clearFields();
        }
    }, 100);
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
        option.value = suggestion.patrons_name;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    patronInput.setAttribute('list', 'datalist-patronnames');
});

// Handle case when user makes a selection or input loses focus for patron name
patronInput.addEventListener('blur', function() {
    const input = this.value.trim();

    // Check for an exact match after a short delay
    setTimeout(() => {
        const selectedPatron = borrowingBooks.find(book => 
            book.patrons_name.toLowerCase() === input.toLowerCase()
        );

        if (selectedPatron) {
            patronIdInput.value = selectedPatron.patrons_id;
        } else {
            // Clear patron-related fields if input doesn't match any suggestion
            patronInput.value = '';
            patronIdInput.value = '';
        }
    }, 100);
});

// Function to clear all the fields
function clearFields() {
    classNumInput.value = '';
    accNumInput.value = '';
    titleInput.value = '';
    bookIdInput.value = '';
    patronInput.value = '';
    patronIdInput.value = '';
}
