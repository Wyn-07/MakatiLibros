
 const categoryInput = document.getElementById('category');

 categoryInput.addEventListener('input', function() {
     let input = this.value;
     let suggestions = categoryNames.filter(name => name.toLowerCase().startsWith(input.toLowerCase()));

     // Limit to top 10 suggestions
     suggestions = suggestions.slice(0, 10);

     // Clear previous suggestions
     let datalist = document.getElementById('datalist-categories');
     if (datalist) {
         datalist.remove();
     }

     // Create new datalist for suggestions
     datalist = document.createElement('datalist');
     datalist.id = 'datalist-categories';

     suggestions.forEach(suggestion => {
         let option = document.createElement('option');
         option.value = suggestion;
         datalist.appendChild(option);
     });

     document.body.appendChild(datalist);
     categoryInput.setAttribute('list', 'datalist-categories');
 });