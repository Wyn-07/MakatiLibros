  const authorInput = document.getElementById('author');

  authorInput.addEventListener('input', function() {
      let input = this.value;
      let suggestions = authorsNames.filter(name => name.toLowerCase().startsWith(input.toLowerCase()));

      // Limit to top 10 suggestions
      suggestions = suggestions.slice(0, 10);

      // Clear previous suggestions
      let datalist = document.getElementById('datalist-authors');
      if (datalist) {
          datalist.remove();
      }

      // Create new datalist for suggestions
      datalist = document.createElement('datalist');
      datalist.id = 'datalist-authors';

      suggestions.forEach(suggestion => {
          let option = document.createElement('option');
          option.value = suggestion;
          datalist.appendChild(option);
      });

      document.body.appendChild(datalist);
      authorInput.setAttribute('list', 'datalist-authors');
  });