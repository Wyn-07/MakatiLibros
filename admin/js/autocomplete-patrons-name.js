const addborrowpatronInput = document.getElementById('addName');

// Create a datalist element outside of the event listener
let datalist = document.createElement('datalist');
datalist.id = 'datalist-patrons';
document.body.appendChild(datalist); // Append datalist to the body once

addborrowpatronInput.setAttribute('list', datalist.id); // Set the input's list attribute

addborrowpatronInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by patron name
    let suggestions = patronsName.filter(patron => patron.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear the previous options in the datalist
    datalist.innerHTML = ''; // Clear previous options

    // Populate the datalist with new suggestions
    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name;
        datalist.appendChild(option);
    });
});









//for edit borrow modal
// Get the input element for editing the borrow modal
const editborrowpatronInput = document.getElementById('editName');

// Create a datalist element outside of the event listener
let editDatalist = document.createElement('datalist');
editDatalist.id = 'datalist-patrons-edit';
document.body.appendChild(editDatalist); // Append datalist to the body once

// Set the input's list attribute once
editborrowpatronInput.setAttribute('list', editDatalist.id);

editborrowpatronInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by patron name
    let suggestions = patronsName.filter(patron => patron.name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear the previous options in the datalist
    editDatalist.innerHTML = ''; // Clear previous options

    // Populate the datalist with new suggestions
    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion.name;
        editDatalist.appendChild(option);
    });
});
