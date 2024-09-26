const categoryInput = document.getElementById('category');
const categoryIdInput = document.getElementById('category_id');

categoryInput.addEventListener('input', function() {
    let input = this.value;
    
    // Filter suggestions by category name
    let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-categories');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-categories';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.category; // Use the category name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    categoryInput.setAttribute('list', 'datalist-categories');
});

// Clear input and set category ID if a valid selection is made
categoryInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

    if (selectedCategory) {
        // Set the category ID in the hidden input field
        categoryIdInput.value = selectedCategory.category_id;
    } else {
        // Clear both inputs if no valid selection is made
        categoryInput.value = '';
        categoryIdInput.value = '';
    }
});







//for edit modal
const editcategoryInput = document.getElementById('edit_category');
const editcategoryIdInput = document.getElementById('edit_category_id'); // Assuming you have a hidden input for category ID

editcategoryInput.addEventListener('input', function() {
    let input = this.value;
    
    // Filter suggestions by category name
    let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-categories');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-categories';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.category; // Use the category name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    editcategoryInput.setAttribute('list', 'datalist-categories');
});

// Clear input and set category ID if a valid selection is made
editcategoryInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

    if (selectedCategory) {
        // Set the category ID in the hidden input field
        editcategoryIdInput.value = selectedCategory.category_id;
    } else {
        // Clear both inputs if no valid selection is made
        editcategoryInput.value = '';
        editcategoryIdInput.value = '';
    }
});







