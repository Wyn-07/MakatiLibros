
const accNumInput = document.getElementById('acc_num');
const classNumInput = document.getElementById('class_num');
const titleInput = document.getElementById('title');
const authorInput = document.getElementById('author');
const categoryInput = document.getElementById('category');
const copyrightInput = document.getElementById('copyright');
const authorIdInput = document.getElementById('author_id');
const categoryIdInput = document.getElementById('category_id');
const imageBookPreview = document.getElementById('imageBookPreview');

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
        
        // Populate the book image
        const imagePath = `../book_images/${selectedBook.image}`; // Update image source
        imageBookPreview.src = imagePath; // Update image source
        
        // Populate the image name
        const imageNameInput = document.getElementById('image');
        imageNameInput.value = selectedBook.image; // Set the image name
    } else {
        clearFields();
    }
});

// Function to clear the fields
function clearFields() {
    classNumInput.value = '';
    accNumInput.value = '';
    titleInput.value = '';
    authorInput.value = ''; 
    categoryInput.value = ''; 
    copyrightInput.value = ''; 
    authorIdInput.value = ''; 
    categoryIdInput.value = ''; 
    imageBookPreview.src = '../book_images/no-image.png'; 
    const imageNameInput = document.getElementById('image');
    imageNameInput.value = ''; // Clear image name
}
