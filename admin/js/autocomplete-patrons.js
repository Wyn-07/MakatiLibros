const patronInput = document.getElementById('patronInput');
const patronIdInput = document.getElementById('patronIdInput');

patronInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by patron name
    let suggestions = patronsName.filter(patron => patron.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-patrons');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-patrons';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name; // Use the patron name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    patronInput.setAttribute('list', 'datalist-patrons');
});

// Clear input and set patron ID if a valid selection is made
patronInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedpatron = patronsName.find(patron => patron.name.toLowerCase() === input.toLowerCase());

    if (selectedpatron) {
        // Set the patron ID in the hidden input field
        patronIdInput.value = selectedpatron.patrons_id;
    } else {
        // Clear both inputs if no valid selection is made
        patronInput.value = '';
        patronIdInput.value = '';
    }
});

// For edit modal
const editpatronInput = document.getElementById('edit_patron');
const editpatronIdInput = document.getElementById('edit_patron_id');

editpatronInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by patron name
    let suggestions = patronsName.filter(patron => patron.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-patrons');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-patrons';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name; // Use the patron name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    editpatronInput.setAttribute('list', 'datalist-patrons');
});

// Clear input and set patron ID if a valid selection is made
editpatronInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedpatron = patronsName.find(patron => patron.name.toLowerCase() === input.toLowerCase());

    if (selectedpatron) {
        // Set the patron ID in the hidden input field
        editpatronIdInput.value = selectedpatron.patrons_id;
    } else {
        // Clear both inputs if no valid selection is made
        editpatronInput.value = '';
        editpatronIdInput.value = '';
    }
});



