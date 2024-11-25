let lastClickedBook = null; // Store the reference to the last clicked book

// Select all books from the correct container
const books = document.querySelectorAll('.row-contents-center .container-books-2');
const modalParent = document.querySelector('.row-books-contents-modal-parent');
const bookDetailsContainer = document.getElementById('book-details');


// Open modal function
function openModal() {
    document.body.style.overflow = 'hidden';  // Disable body scroll
    modalParent.style.display = 'flex';  // Show modal
}

// Close modal function
function closeModal() {
    document.body.style.overflow = 'auto';  // Enable body scroll again
    modalParent.style.display = 'none';  // Hide modal
}

// Add click event listener to each book
books.forEach(book => {
    book.addEventListener('click', () => {
        // Store the reference to the clicked book
        lastClickedBook = book;

        // Get book details
        const bookId = book.querySelector('.books-id').textContent;
        const bookStatus = book.querySelector('.books-status').textContent;
        const bookCategory = book.querySelector('.books-categories').textContent;

        const bookTitle = book.querySelector('.books-name-2').textContent;
        const bookImage = book.querySelector('.books-image-2 img').src;
        const bookAuthor = book.querySelector('.books-author').textContent;
        const bookCopyright = book.querySelector('.books-copyright').textContent;
        const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';


        // Update the book-details container with the clicked book's information
        const bookDetailsContainer = document.getElementById('book-details');
        bookDetailsContainer.querySelector('.books-contents-id').textContent = bookId;
        bookDetailsContainer.querySelector('.books-contents-category').textContent = bookCategory;

        bookDetailsContainer.querySelector('.books-contents-name').textContent = bookTitle;
        bookDetailsContainer.querySelector('.books-contents-image').innerHTML = `<img src="${bookImage}" class="image">`;
        bookDetailsContainer.querySelector('.books-contents-author').textContent = bookAuthor;
        bookDetailsContainer.querySelector('.books-contents-copyright').textContent = bookCopyright;
        bookDetailsContainer.querySelector('.books-contents-ratings').textContent = bookRating;

        bookDetailsContainer.querySelector('.ratings-number').textContent = bookRating;



        // Display the book-details container
        bookDetailsContainer.style.display = 'flex';


        // Open the modal
        openModal();  // Call the openModal function here


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
    bookDetailsContainer.style.display = 'none';  // Hide the book details container
    closeModal();  // Call the closeModal function to close the modal and re-enable scrolling
});
