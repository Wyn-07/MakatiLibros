
var firstNameInput = document.getElementById("fname");
var middleNameInput = document.getElementById("mname");
var lastNameInput = document.getElementById("lname");
var suffixInput = document.getElementById("suffix");

function preventNumbersAndSpecialChars(event) {
    var inputValue = event.target.value;
    var newValue = inputValue.replace(/[^a-zA-Z\s]/g, ''); // Remove any character that is not a letter or space
    event.target.value = newValue;
}

function allowHypen(event) {
    var inputValue = event.target.value;
    var newValue = inputValue.replace(/[^a-zA-Z\s-]/g, ''); // Allow letters, spaces, and hyphens
    event.target.value = newValue;
}

function allowPeriod(event) {
    var inputValue = event.target.value;
    var newValue = inputValue.replace(/[^a-zA-Z\s.]/g, ''); // Allow letters, spaces, and hyphens
    event.target.value = newValue;
}


firstNameInput.addEventListener("input", preventNumbersAndSpecialChars);
middleNameInput.addEventListener("input", allowHypen);
lastNameInput.addEventListener("input", allowHypen);
suffixInput.addEventListener("input", allowPeriod);



function preventSpecialChars(event) {
    var inputValue = event.target.value;
    // Allow letters, numbers, spaces, hyphens, and periods
    var newValue = inputValue.replace(/[^a-zA-Z0-9\s.-]/g, '');
    event.target.value = newValue;
}



function capitalize(input) {
    var inputValue = input.value;
    var words = inputValue.split(' ');

    var capitalizedWords = words.map(function(word) {
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    });

    var capitalizedValue = capitalizedWords.join(' ');

    input.value = capitalizedValue;
}

function disableSpace(event) {
    var input = event.target;
    if (event.key === ' ' && input.selectionStart === 0) {
        event.preventDefault();
    }
}


window.onload = function() {
    calculateMaxBirthDate();
};

function calculateMaxBirthDate() {
    var today = new Date();
    var maxDate = new Date(today.getFullYear() - 4, today.getMonth(), today.getDate());

    // Format maxDate as yyyy-mm-dd
    var maxDateString = maxDate.toISOString().split('T')[0];

    // Set the max attribute of the birthdate input field
    document.getElementById('birthdate').setAttribute('max', maxDateString);
}


function calculateAge() {
    var birthdateInput = document.getElementById('birthdate');
    var ageInput = document.getElementById('age');

    var birthdate = new Date(birthdateInput.value);
    var today = new Date();

    var age = today.getFullYear() - birthdate.getFullYear();

    // Adjust age if birthday hasn't occurred yet this year
    if (today.getMonth() < birthdate.getMonth() ||
        (today.getMonth() === birthdate.getMonth() && today.getDate() < birthdate.getDate())) {
        age--;
    }

    ageInput.value = age;
}

function handleInput(input) {
    if (!isNaN(input.value)) {
        input.value = input.value.replace(/\D/g, '');

        input.value = "+" + input.value;

        if (!input.value.startsWith("+639")) {
            input.value = "+639" + input.value.slice(4);
        }

        if (input.value.length > 13) {
            input.value = input.value.slice(0, 13);
        }
    } else {
        input.value = "+" + input.value.replace(/\D/g, '');
    }
}



function validateForm() {
    var resultErrorContainer = document.getElementById("container-error");
    var message = document.getElementById("message");
    message.innerHTML = "";

    var isValid = true;

    // Array of required field IDs
    var requiredFields = ["fname", "lname", "contact", "birthdate", "age", "gender", "contact", "house_num", "streets", "barangay", "company_name", "company_contact", "company_address",
          "profile_image", "valid_id", "grtrfname", "grtrlname", "grtrcontact", "grtraddress", "grtrcompany_name", "grtrcompany_contact", "grtrcompany_address", "guarantor_sign", "patron_sign"];
    var contactInput = document.getElementById("contact");

    // Validate required fields (no message, just border)
    requiredFields.forEach(function(fieldId) {
        var input = document.getElementById(fieldId);
        if (!input.value.trim()) { // Check if the field is empty
            isValid = false;
            input.style.border = "2px solid red"; // Set the border to red
        } else {
            input.style.border = ""; // Reset the border if valid
        }
    });

    // Validate contact input (with error message)
    if (contactInput.value.length < 13) {
        isValid = false; // Set valid to false if input is invalid
        contactInput.style.border = "2px solid red"; // Set the border to red
        message.innerHTML = "Contact number must be 13 characters long."; // Set the error message
        resultErrorContainer.style.display = "flex"; // Show the error container
        message.style.display = "block"; // Display the message
    } else {
        contactInput.style.border = ""; 
        resultErrorContainer.style.display = "none"; // Hide error container if all inputs are valid
        message.style.display = "none"; // Hide message
    }


    return isValid; // Return true if all inputs are valid
}

