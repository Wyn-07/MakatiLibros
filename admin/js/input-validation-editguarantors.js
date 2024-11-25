
var firstNameInput = document.getElementById("editFirstname");
var middleNameInput = document.getElementById("editMiddlename");
var lastNameInput = document.getElementById("editLastname");
var suffixInput = document.getElementById("editSuffix");

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


var addressInput = document.getElementById("editAddress");

function preventSpecialChars(event) {
    var inputValue = event.target.value;
    // Allow letters, numbers, spaces, hyphens, and periods
    var newValue = inputValue.replace(/[^a-zA-Z0-9\s.-]/g, '');
    event.target.value = newValue;
}


addressInput.addEventListener("input", preventSpecialChars);
companyAddressInput.addEventListener("input", preventSpecialChars);

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



function validateEditForm(contactInputId) {
    var resultErrorContainer = document.getElementById("container-error-editguarantor");
    var message = document.getElementById("message-editguarantor");
    message.innerHTML = "";

    var isValid = true; // Initialize isValid to true

    // Validate contact input
    var contactInput = document.getElementById(contactInputId);
    if (contactInput.value.length < 13) {
        isValid = false; // Set isValid to false if input is invalid
        contactInput.style.border = "2px solid red"; // Set the border to red
        resultErrorContainer.style.display = "flex"; // Show the error container
        message.innerHTML = "Contact number must be 13 characters long."; // Set the error message
        message.style.display = "block"; // Display the message
    } else {
        contactInput.style.border = ""; // Reset the border if valid
    }

    if (isValid) {
        resultErrorContainer.style.display = "none"; // Hide error container if valid
        message.style.display = "none"; // Hide error message
    }

    return isValid; // Return the result of the validation
}
