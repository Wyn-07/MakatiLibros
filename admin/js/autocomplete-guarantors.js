const guarantorInput = document.getElementById('guarantorInput');
const guarantorIdInput = document.getElementById('guarantorIdInput');

guarantorInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by guarantor name
    let suggestions = guarantorsName.filter(guarantor => guarantor.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-guarantors');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-guarantors';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name; // Use the guarantor name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    guarantorInput.setAttribute('list', 'datalist-guarantors');
});

// Clear input and set guarantor ID if a valid selection is made
guarantorInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedGuarantor = guarantorsName.find(guarantor => guarantor.name.toLowerCase() === input.toLowerCase());

    if (selectedGuarantor) {
        // Set the guarantor ID in the hidden input field
        guarantorIdInput.value = selectedGuarantor.guarantor_id;
    } else {
        // Clear both inputs if no valid selection is made
        guarantorInput.value = '';
        guarantorIdInput.value = '';
    }
});

// For edit modal
const editGuarantorInput = document.getElementById('edit_guarantor');
const editGuarantorIdInput = document.getElementById('edit_guarantor_id');

editGuarantorInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by guarantor name
    let suggestions = guarantorsName.filter(guarantor => guarantor.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-guarantors');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-guarantors';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name; // Use the guarantor name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    editGuarantorInput.setAttribute('list', 'datalist-guarantors');
});

// Clear input and set guarantor ID if a valid selection is made
editGuarantorInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the suggestions
    const selectedGuarantor = guarantorsName.find(guarantor => guarantor.name.toLowerCase() === input.toLowerCase());

    if (selectedGuarantor) {
        // Set the guarantor ID in the hidden input field
        editGuarantorIdInput.value = selectedGuarantor.guarantor_id;
    } else {
        // Clear both inputs if no valid selection is made
        editGuarantorInput.value = '';
        editGuarantorIdInput.value = '';
    }
});
