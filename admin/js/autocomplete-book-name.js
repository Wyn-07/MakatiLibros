const addBookTitleInput = document.getElementById('addBookTitle'); 
const addBookTitleIDInput = document.getElementById('addBookTitleId'); 

if (addBookTitleInput) {
    addBookTitleInput.addEventListener('input', function () {
        let input = this.value;

        let suggestions = bookList.filter(book => book.title.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-books');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-books'; 

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.title; 
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);
        addBookTitleInput.setAttribute('list', 'datalist-books'); 
    });

    addBookTitleInput.addEventListener('blur', function () {
        const input = this.value;

        const selectedBook = bookList.find(book => book.title.toLowerCase() === input.toLowerCase());

        if (selectedBook) {
            addBookTitleIDInput.value = selectedBook.book_id; 
        } else {
            addBookTitleInput.value = '';
            addBookTitleIDInput.value = ''; 
        }
    });
} 








const editBookTitleInput = document.getElementById('editBookTitle'); 
const editBookTitleIDInput = document.getElementById('editBookTitleId'); 

if (editBookTitleInput) {
    editBookTitleInput.addEventListener('input', function () {
        let input = this.value;

        let suggestions = bookList.filter(book => book.title.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-books');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-books'; 

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.title; 
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);
        editBookTitleInput.setAttribute('list', 'datalist-books'); 
    });

    editBookTitleInput.addEventListener('blur', function () {
        const input = this.value;

        const selectedBook = bookList.find(book => book.title.toLowerCase() === input.toLowerCase());

        if (selectedBook) {
            editBookTitleIDInput.value = selectedBook.book_id; 
        } else {
            editBookTitleInput.value = '';
            editBookTitleIDInput.value = ''; 
        }
    });
}

