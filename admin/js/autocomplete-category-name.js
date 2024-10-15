// For book add modal
const addbookcategoryInput = document.getElementById('category');
const categoryIdInput = document.getElementById('category_id');


if (addbookcategoryInput) {
    addbookcategoryInput.addEventListener('input', function () {
        let input = this.value;

        let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-categories');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-categories';

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.category;
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);

        addbookcategoryInput.setAttribute('list', 'datalist-categories');
    });

    addbookcategoryInput.addEventListener('blur', function () {
        const input = this.value;

        const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

        if (selectedCategory) {
            categoryIdInput.value = selectedCategory.category_id;
        } else {
            addbookcategoryInput.value = '';
            categoryIdInput.value = '';
        }
    });
}




// For editt book add modal
const editbookcategoryInput = document.getElementById('edit_category');
const editcategoryIdInput = document.getElementById('edit_category_id');


if (editbookcategoryInput) {
    editbookcategoryInput.addEventListener('input', function () {
        let input = this.value;

        let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-categories');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-categories';

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.category;
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);

        editbookcategoryInput.setAttribute('list', 'datalist-categories');
    });

    editbookcategoryInput.addEventListener('blur', function () {
        const input = this.value;

        const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

        if (selectedCategory) {
            editcategoryIdInput.value = selectedCategory.category_id;
        } else {
            editbookcategoryInput.value = '';
            editcategoryIdInput.value = '';
        }
    });
}












const addBookLogsCategory = document.getElementById('addBorrowLogCategory');
const addBookLogsIDCategory = document.getElementById('addBorrowLogCategoryId');

if (addBookLogsCategory) {
    addBookLogsCategory.addEventListener('input', function () {
        let input = this.value;

        let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-categories');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-categories';

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.category;
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);
        addBookLogsCategory.setAttribute('list', 'datalist-categories');
    });

    addBookLogsCategory.addEventListener('blur', function () {
        const input = this.value;

        const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

        if (selectedCategory) {
            addBookLogsIDCategory.value = selectedCategory.category_id;
        } else {
            addBookLogsCategory.value = '';
            addBookLogsIDCategory.value = '';
        }
    });
}






// For edit borrow modal
const editBookLogsCategory = document.getElementById('editBorrowLogCategory');
const editBookLogsCategoryId = document.getElementById('editBorrowLogCategoryId');

if (editBookLogsCategory) {
    editBookLogsCategory.addEventListener('input', function () {
        let input = this.value;

        let suggestions = categoryList.filter(category => category.category.toLowerCase().startsWith(input.toLowerCase()));

        suggestions = suggestions.slice(0, 10);

        let datalist = document.getElementById('datalist-categories');
        if (datalist) {
            datalist.remove();
        }

        datalist = document.createElement('datalist');
        datalist.id = 'datalist-categories';

        suggestions.forEach(suggestion => {
            let option = document.createElement('option');
            option.value = suggestion.category;
            datalist.appendChild(option);
        });

        document.body.appendChild(datalist);

        editBookLogsCategory.setAttribute('list', 'datalist-categories');
    });

    editBookLogsCategory.addEventListener('blur', function () {
        const input = this.value;
        const selectedCategory = categoryList.find(category => category.category.toLowerCase() === input.toLowerCase());

        if (selectedCategory) {
            editBookLogsCategoryId.value = selectedCategory.category_id;
        } else {
            editBookLogsCategory.value = '';
            editBookLogsCategoryId.value = '';
        }
    });
}


