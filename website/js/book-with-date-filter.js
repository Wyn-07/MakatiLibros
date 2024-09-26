document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filter-form');
    const clearButton = document.getElementById('clear-filters');
    const searchInput = document.getElementById('search');
    const bookContainers = document.querySelectorAll('.container-books');
    const containerUnavailable = document.getElementById('not-found-message');

    function applyFiltersAndSearch() {
        const formData = new FormData(filterForm);
        const selectedCategories = formData.getAll('category[]');
        const selectedDate = formData.get('filter_date');
        const searchQuery = searchInput.value.toLowerCase();

        let booksFound = false;

        bookContainers.forEach(container => {
            const bookCategory = container.querySelector('.books-category').textContent.trim().toLowerCase();
            const bookDate = container.closest('.contents').querySelector('.row-between div').textContent.trim();
            const title = container.querySelector('.books-name').textContent.toLowerCase();
            const author = container.querySelector('.books-author').textContent.toLowerCase();

            // Normalize categories for comparison
            const normalizedBookCategory = bookCategory.toLowerCase().replace(' ', '-');

            // Parse the book date and selected date as Date objects
            const parsedBookDate = new Date(bookDate);
            const parsedSelectedDate = selectedDate ? new Date(selectedDate) : null;

            // Check if the book matches the selected categories
            let matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(normalizedBookCategory);

            // Check if the book matches the selected date (considering only year, month, and day)
            let matchesDate = !selectedDate || (
                parsedBookDate.getFullYear() === parsedSelectedDate.getFullYear() &&
                parsedBookDate.getMonth() === parsedSelectedDate.getMonth() &&
                parsedBookDate.getDate() >= parsedSelectedDate.getDate()
            );

            // Check if the book matches the search query
            let matchesSearch = title.includes(searchQuery) || author.includes(searchQuery);

            if (matchesCategory && matchesDate && matchesSearch) {
                container.style.display = 'block';
                booksFound = true;
            } else {
                container.style.display = 'none';
            }
        });

        // Show or hide the entire content section based on whether any books match the filters and search
        document.querySelectorAll('.contents').forEach(content => {
            if (content.querySelectorAll('.container-books[style="display: block;"]').length > 0) {
                content.style.display = 'flex';
            } else {
                content.style.display = 'none';
            }
        });

        // Show the "Not Found" message if no books were found
        containerUnavailable.style.display = booksFound ? 'none' : 'flex';
    }

    filterForm.addEventListener('change', applyFiltersAndSearch);
    searchInput.addEventListener('input', applyFiltersAndSearch);
    clearButton.addEventListener('click', function () {
        filterForm.reset();
        searchInput.value = '';
        applyFiltersAndSearch();
    });

    applyFiltersAndSearch();
});
