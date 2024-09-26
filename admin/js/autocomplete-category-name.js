// For borrow add modal
const addborrowcategoryInput = document.getElementById('addCategory');

// Add an event listener for the input event
addborrowcategoryInput.addEventListener('input', function() {
    let input = this.value;

    // Filter categories based on user input
    let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

    // Limit the suggestions to the top 10
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-categories');
    if (datalist) {
        datalist.remove();
    }

    // Create a new datalist for category suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-categories';

    // Populate the datalist with suggestions
    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.category;
        datalist.appendChild(option);
    });

    // Append the datalist to the document body
    document.body.appendChild(datalist);

    // Set the datalist for the category input
    addborrowcategoryInput.setAttribute('list', 'datalist-categories');
});

// Add an event listener for the blur event
addborrowcategoryInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

    if (!selectedCategory) {
        // Clear the input if no valid selection is made
        addborrowcategoryInput.value = '';
    }
});










//for editborrow
const editborrowcategoryInput = document.getElementById('editCategory');

editborrowcategoryInput.addEventListener('input', function() {
    let input = this.value;

    let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

    suggestions = suggestions.slice(0, 10);

    let datalist = document.getElementById('datalist-categories');
    if (datalist) {
        datalist.remove();
    }

    datalist = document.createElement('datalist');
    datalist.id = 'datalist-categories';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.category;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);

    editborrowcategoryInput.setAttribute('list', 'datalist-categories');
});

editborrowcategoryInput.addEventListener('blur', function() {
    const input = this.value;

    const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

    if (!selectedCategory) {
        editborrowcategoryInput.value = '';
    }
});
