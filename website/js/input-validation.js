
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

var addressInput = document.getElementById("address");

function preventSpecialChars(event) {
    var inputValue = event.target.value;
    // Allow letters, numbers, spaces, hyphens, and periods
    var newValue = inputValue.replace(/[^a-zA-Z0-9\s.-]/g, '');
    event.target.value = newValue;
}


addressInput.addEventListener("input", preventSpecialChars);

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
    var maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

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
