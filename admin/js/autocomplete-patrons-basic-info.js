const addFirstnameInput = document.getElementById('addFirstname');
const addMiddlenameInput = document.getElementById('addMiddlename');
const addLastnameInput = document.getElementById('addLastname');
const addSuffixInput = document.getElementById('addSuffix');
const addAgeInput = document.getElementById('addAge');
const addGenderInput = document.getElementById('addGender');

// Event listener for input in first name field
addFirstnameInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by first name
    let suggestions = patronsBasicInfo.filter(patron => patron.firstname.toLowerCase().startsWith(input.toLowerCase()));

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
        // Combine full name for suggestions
        let fullName = `${suggestion.firstname} ${suggestion.middlename || ''} ${suggestion.lastname} ${suggestion.suffix || ''}`.trim();
        option.value = fullName; // Use the full name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    addFirstnameInput.setAttribute('list', 'datalist-patrons');
});

// Populate all fields when a valid selection is made
addFirstnameInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the full names
    const selectedPatron = patronsBasicInfo.find(patron => {
        let fullName = `${patron.firstname} ${patron.middlename || ''} ${patron.lastname} ${patron.suffix || ''}`.trim();
        return fullName.toLowerCase() === input.toLowerCase();
    });

    if (selectedPatron) {
        // Populate all modal fields with selected patron's info
        addFirstnameInput.value = selectedPatron.firstname;
        addMiddlenameInput.value = selectedPatron.middlename;
        addLastnameInput.value = selectedPatron.lastname;
        addSuffixInput.value = selectedPatron.suffix;
        addAgeInput.value = selectedPatron.age;
        addGenderInput.value = selectedPatron.gender;
    } 
});











const editFirstnameInput = document.getElementById('editFirstname');
const editMiddlenameInput = document.getElementById('editMiddlename');
const editLastnameInput = document.getElementById('editLastname');
const editSuffixInput = document.getElementById('editSuffix');
const editAgeInput = document.getElementById('editAge');
const editGenderInput = document.getElementById('editGender');

// Event listener for input in edit first name field
editFirstnameInput.addEventListener('input', function() {
    let input = this.value;

    // Filter suggestions by first name
    let suggestions = patronsBasicInfo.filter(patron => patron.firstname.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-edit-patrons');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-edit-patrons';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        // Combine full name for suggestions
        let fullName = `${suggestion.firstname} ${suggestion.middlename || ''} ${suggestion.lastname} ${suggestion.suffix || ''}`.trim();
        option.value = fullName; // Use the full name for suggestions
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    editFirstnameInput.setAttribute('list', 'datalist-edit-patrons');
});

// Populate all fields when a valid selection is made in edit modal
editFirstnameInput.addEventListener('blur', function() {
    const input = this.value;

    // Check if input matches any of the full names
    const selectedPatron = patronsBasicInfo.find(patron => {
        let fullName = `${patron.firstname} ${patron.middlename || ''} ${patron.lastname} ${patron.suffix || ''}`.trim();
        return fullName.toLowerCase() === input.toLowerCase();
    });

    if (selectedPatron) {
        // Populate all edit modal fields with selected patron's info
        editFirstnameInput.value = selectedPatron.firstname;
        editMiddlenameInput.value = selectedPatron.middlename;
        editLastnameInput.value = selectedPatron.lastname;
        editSuffixInput.value = selectedPatron.suffix;
        editAgeInput.value = selectedPatron.age;
        editGenderInput.value = selectedPatron.gender;
    } 
});
