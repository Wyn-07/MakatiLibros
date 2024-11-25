// Get necessary input elements
const borrowIdInput = document.getElementById('borrowIDInput'); // Input for borrow ID
const patronIdInput = document.getElementById('patronIdInput');
const patronInput = document.getElementById('patronInput');
const guarantorInput = document.getElementById('guarantorInput');
const guarantorIdInput = document.getElementById('guarantorIdInput');

// Event listener for borrow ID input to provide suggestions
borrowIdInput.addEventListener('input', function () {
    const input = this.value;

    // Filter suggestions based on input
    let suggestions = patronInfo.filter(patron => {
        return patron.borrow_id && patron.borrow_id.toString().startsWith(input); // Check if borrow_id starts with the input
    }).slice(0, 10); // Limit to top 10 suggestions

    // Remove previous datalist if it exists
    let datalist = document.getElementById('datalist-borrow-ids');
    if (datalist) datalist.remove();

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-borrow-ids';

    suggestions.forEach(suggestion => {
        const option = document.createElement('option');
        option.value = suggestion.borrow_id; // Populate suggestion with borrow_id
        datalist.appendChild(option);
    });

    // Attach datalist to the document
    document.body.appendChild(datalist);
    borrowIdInput.setAttribute('list', 'datalist-borrow-ids');
});

// Event listener for borrow ID input blur to fill in patron and guarantor information
borrowIdInput.addEventListener('blur', function () {
    const input = this.value;

    // Find matching patron from the list based on borrow ID
    const selectedPatron = patronInfo.find(patron => patron.borrow_id.toString() === input);

    // Log for debugging purposes
    console.log("Borrow ID input:", input);
    console.log("Selected Patron:", selectedPatron); // Should log 'undefined' if not found

    if (selectedPatron) {
        // Set Patron and Guarantor details in the form
        patronIdInput.value = selectedPatron.patrons_id;
        patronInput.value = `${selectedPatron.patron_firstname} ${selectedPatron.patron_middlename} ${selectedPatron.patron_lastname}`;
        guarantorInput.value = `${selectedPatron.guarantor_firstname} ${selectedPatron.guarantor_middlename} ${selectedPatron.guarantor_lastname}`;
        guarantorIdInput.value = selectedPatron.guarantor_id;
    } else {
        // Clear inputs if no valid selection
        patronIdInput.value = '';
        patronInput.value = '';
        guarantorInput.value = '';
        guarantorIdInput.value = '';
        
        // Clear the borrow ID input field if no matching patron is found
        this.value = '';  // This line clears the borrow ID input field
    }
});
