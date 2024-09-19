const titleInput = document.getElementById('titleInput');

titleInput.addEventListener('input', function () {
    let input = this.value;
    let suggestions = bookTitles.filter(title => title.toLowerCase().startsWith(input.toLowerCase()));

    // Limit to top 10 suggestions
    suggestions = suggestions.slice(0, 10);

    // Clear previous suggestions
    let datalist = document.getElementById('datalist-title');
    if (datalist) {
        datalist.remove();
    }

    // Create new datalist for suggestions
    datalist = document.createElement('datalist');
    datalist.id = 'datalist-title';

    suggestions.forEach(suggestion => {
        let option = document.createElement('option');
        option.value = suggestion;
        datalist.appendChild(option);
    });

    document.body.appendChild(datalist);
    titleInput.setAttribute('list', 'datalist-title');
});