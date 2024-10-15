let lastClickedBook = null;

// Select all books from both rows
const books = document.querySelectorAll('.row-books .container-books');

// Add click event listener to each book
books.forEach(book => {
    book.addEventListener('click', () => {
        // Store the reference to the clicked book
        lastClickedBook = book;

        // Get book details
        const bookId = book.querySelector('.books-id').textContent;

        const bookTitle = book.querySelector('.books-name').textContent;
        const bookImage = book.querySelector('.books-image img').src;
        const bookAuthor = book.querySelector('.books-author').textContent;
        const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';

        // Update the book-details container with the clicked book's information
        const bookDetailsContainer = document.getElementById('book-details');
        bookDetailsContainer.querySelector('.books-contents-id').textContent = bookId;

        bookDetailsContainer.querySelector('.books-contents-name').textContent = bookTitle;
        bookDetailsContainer.querySelector('.books-contents-image').innerHTML = `<img src="${bookImage}" class="image">`;
        bookDetailsContainer.querySelector('.books-contents-author').textContent = bookAuthor;
        bookDetailsContainer.querySelector('.books-contents-ratings').textContent = bookRating;

        bookDetailsContainer.querySelector('.ratings-number').textContent = bookRating;

        // Ensure the book-details container is inserted after the clicked book's container
        const clickedContents = book.closest('.contents, .contents-big-padding');
        clickedContents.parentNode.insertBefore(bookDetailsContainer, clickedContents.nextSibling);


        // Display the book-details container
        bookDetailsContainer.style.display = 'flex';

        // Scroll into view
        bookDetailsContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'end'
        });




        // Handle star ratings
        const stars = document.querySelectorAll('.star');
        let rating = parseFloat(bookRating);

        if (!isNaN(rating)) {
            rating = Math.round(rating);

            stars.forEach(star => {
                const value = parseFloat(star.getAttribute('data-value'));
                if (value <= rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }
    });






});

// Close button functionality
const closeButton = document.querySelector('.button-close');
closeButton.addEventListener('click', () => {
    document.getElementById('book-details').style.display = 'none';

    // Scroll back to the last clicked book
    if (lastClickedBook) {
        lastClickedBook.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});
