const authorInput = document.getElementById('author');
const authorIdInput = document.getElementById('author_id');

authorInput.addEventListener('input', function() {
    let input = this.value;
    
    // Filter suggestions by author name
    let suggestions = authorsList.filter(author => author.author.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-authors');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-authors';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.author; // Use the author name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    authorInput.setAttribute('list', 'datalist-authors');
});

// Clear input and set author ID if a valid selection is made
authorInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedAuthor = authorsList.find(author => author.author.toLowerCase() === input.toLowerCase());

    if (selectedAuthor) {
        // Set the author ID in the hidden input field
        authorIdInput.value = selectedAuthor.author_id;
    } else {
        // Clear both inputs if no valid selection is made
        authorInput.value = '';
        authorIdInput.value = '';
    }
});






//for edit modal
const editauthorInput = document.getElementById('edit_author');
const editauthorIdInput = document.getElementById('edit_author_id');

editauthorInput.addEventListener('input', function() {
    let input = this.value;
    
    // Filter suggestions by author name
    let suggestions = authorsList.filter(author => author.author.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-authors');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-authors';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.author; // Use the author name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    editauthorInput.setAttribute('list', 'datalist-authors');
});

// Clear input and set author ID if a valid selection is made
editauthorInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedAuthor = authorsList.find(author => author.author.toLowerCase() === input.toLowerCase());

    if (selectedAuthor) {
        // Set the author ID in the hidden input field
        editauthorIdInput.value = selectedAuthor.author_id;
    } else {
        // Clear both inputs if no valid selection is made
        editauthorInput.value = '';
        editauthorIdInput.value = '';
    }
});