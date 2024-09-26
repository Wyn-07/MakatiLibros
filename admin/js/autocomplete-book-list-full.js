const accNumInput = document.getElementById('acc_num');
const classNumInput = document.getElementById('class_num');
const titleInput = document.getElementById('title');
const authorInput = document.getElementById('author'); // Input for author
const categoryInput = document.getElementById('category'); // Input for category
const copyrightInput = document.getElementById('copyright'); // Input for copyright
const authorIdInput = document.getElementById('author_id'); // New input for author_id
const categoryIdInput = document.getElementById('category_id'); // New input for category_id

// Event listener for accession number input
accNumInput.addEventListener('input', function() {
    let input = this.value.trim();

    // Filter suggestions by accession number
    let suggestions = bookList.filter(book => 
        book.acc_number.toLowerCase().includes(input.toLowerCase())
    );

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-accnums');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-accnums';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.acc_number;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    accNumInput.setAttribute('list', 'datalist-accnums');
});

// Handle case when user makes a selection or input loses focus for accession number
accNumInput.addEventListener('blur', function() {
    const input = this.value.trim();

    const selectedBook = bookList.find(book => 
        book.acc_number.toLowerCase() === input.toLowerCase()
    );

    if (selectedBook) {
        classNumInput.value = selectedBook.class_number;
        titleInput.value = selectedBook.title;
        authorInput.value = selectedBook.author_name; // Populate author
        categoryInput.value = selectedBook.category_name; // Populate category
        copyrightInput.value = selectedBook.copyright; // Populate copyright
        authorIdInput.value = selectedBook.author_id; // Populate author_id
        categoryIdInput.value = selectedBook.category_id; // Populate category_id
    } else {
        clearFields();
    }
});

// Event listener for title input
titleInput.addEventListener('input', function() {
    let input = this.value.trim();

    // Filter suggestions by title
    let suggestions = bookList.filter(book => 
        book.title.toLowerCase().includes(input.toLowerCase())
    );

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-titles');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-titles';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.title;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    titleInput.setAttribute('list', 'datalist-titles');
});

// Handle case when user makes a selection or input loses focus for title
titleInput.addEventListener('blur', function() {
    const input = this.value.trim();

    const selectedBook = bookList.find(book => 
        book.title.toLowerCase() === input.toLowerCase()
    );

    if (selectedBook) {
        classNumInput.value = selectedBook.class_number;
        accNumInput.value = selectedBook.acc_number;
        authorInput.value = selectedBook.author_name; // Populate author
        categoryInput.value = selectedBook.category_name; // Populate category
        copyrightInput.value = selectedBook.copyright; // Populate copyright
        authorIdInput.value = selectedBook.author_id; // Populate author_id
        categoryIdInput.value = selectedBook.category_id; // Populate category_id
    } else {
        clearFields();
    }
});

// Function to clear the fields
function clearFields() {
    classNumInput.value = '';
    accNumInput.value = '';
    titleInput.value = '';
    authorInput.value = ''; // Clear author
    categoryInput.value = ''; // Clear category
    copyrightInput.value = ''; // Clear copyright
    authorIdInput.value = ''; // Clear author_id
    categoryIdInput.value = ''; // Clear category_id
}
