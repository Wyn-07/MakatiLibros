// JavaScript to implement autocomplete

const nameInput = document.getElementById('nameInput');

nameInput.addEventListener('input', function() {
    let input = this.value;
    let suggestions = patronsName.filter(name => name.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    nameInput.setAttribute('list', 'datalist');
});