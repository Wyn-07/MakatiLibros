document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const containerUnavailable = document.getElementById('not-found-message');
    const dateSections = document.querySelectorAll('#transactionDate'); // Select each date section

    function applySearch() {
        const searchQuery = searchInput.value.trim().toLowerCase();
        let booksFound = false;

        // Loop through each date section
        dateSections.forEach(section => {
            const booksInDateContainer = section.nextElementSibling; // Get the books container next to the date section
            const booksInDate = booksInDateContainer.querySelectorAll('.container-books');
            let booksFoundInDate = false;

            // Loop through each book in this date section
            booksInDate.forEach(container => {
                const title = container.querySelector('.books-name').textContent.toLowerCase();
                const author = container.querySelector('.books-author').textContent.toLowerCase();

                // Check if the book matches the search query
                let matchesSearch = title.includes(searchQuery) || author.includes(searchQuery);

                // Show or hide the book based on the search query
                if (matchesSearch) {
                    container.style.display = 'block';
                    booksFoundInDate = true; // At least one book found in this section
                    booksFound = true; // At least one book found overall
                } else {
                    container.style.display = 'none';
                }
            });

            // If no books match the search in this date section, hide the whole date section and its books
            if (booksFoundInDate) {
                section.style.display = 'flex'; // Show the date header
                booksInDateContainer.style.display = 'flex'; // Show the books container
            } else {
                section.style.display = 'none'; // Hide the date header
                booksInDateContainer.style.display = 'none'; // Hide the books container
            }
        });

        // Show the "Not Found" message if no books were found across all sections
        containerUnavailable.style.display = booksFound ? 'none' : 'flex';
    }

    // Event listener for search input
    searchInput.addEventListener('input', applySearch);

    // Initial search application
    applySearch();
});
