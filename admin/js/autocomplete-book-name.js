// For add borrow modal
const addborrowbookInput = document.getElementById('addBookTitle');

// Add an event listener for the input event
addborrowbookInput.addEventListener('input', function() {
    let input = this.value;

    // Filter book titles based on user input
    let suggestions = bookList.filter(book => book.title.toLowerCase().startsWith(input.toLowerCase()));

    // Limit the suggestions to the top 10
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-books');
    if (datalist) {
        datalist.remove();
    }

    // Create a new datalist for book title suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-books';

    // Populate the datalist with suggestions
    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.title;
        datalist.appendChild(option);
    });

    // Append the datalist to the document body
    document.body.appendChild(datalist);

    // Set the datalist for the book input
    addborrowbookInput.setAttribute('list', 'datalist-books');
});

// Add an event listener for the blur event
addborrowbookInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedBook = bookList.find(book => book.title.toLowerCase() === input.toLowerCase());

    if (!selectedBook) {
        // Clear the input if no valid selection is made
        addborrowbookInput.value = '';
    }
});










// For edit borrow modal
const editborrowbookInput = document.getElementById('editBookTitle');

editborrowbookInput.addEventListener('input', function() {
    let input = this.value;

    let suggestions = bookList.filter(book => book.title.toLowerCase().startsWith(input.toLowerCase()));

    suggestions = suggestions.slice(0, 10);

    let datalist = document.getElementById('datalist-books');
    if (datalist) {
        datalist.remove();
    }

    datalist = document.createElement('datalist');
    datalist.id = 'datalist-books';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.title;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);

    editborrowbookInput.setAttribute('list', 'datalist-books');
});

editborrowbookInput.addEventListener('blur', function() {
    const input = this.value;

    const selectedBook = bookList.find(book => book.title.toLowerCase() === input.toLowerCase());

    if (!selectedBook) {
        editborrowbookInput.value = '';
    }
});
