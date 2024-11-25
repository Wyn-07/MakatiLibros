let lastClickedBook = null; // Store the reference to the last clicked book

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

books.forEach(book => {
    book.addEventListener('click', () => {
        lastClickedBook = book;

        // Get book details
        const bookId = book.querySelector('.books-id').textContent;
        const bookStatus = book.querySelector('.books-status').textContent;
        const bookCategory = book.querySelector('.books-categories').textContent;
        const bookBorrowStatus = book.querySelector('.books-borrow-status').textContent;
        const bookFavorite = book.querySelector('.books-favorite').textContent;
        const bookTitle = book.querySelector('.books-name-2').textContent;
        const bookImage = book.querySelector('.books-image-2 img').src;
        const bookAuthor = book.querySelector('.books-author').textContent;
        const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';
        const bookUserRating = book.querySelector('.books-user-ratings') ? book.querySelector('.books-user-ratings').textContent : '0';

        // Update the book-details container with the clicked book's information
        bookDetailsContainer.querySelector('.books-contents-id').textContent = bookId;
        bookDetailsContainer.querySelector('.books-contents-category').textContent = bookCategory;
        bookDetailsContainer.querySelector('.books-contents-borrow-status').textContent = bookBorrowStatus;
        bookDetailsContainer.querySelector('.books-contents-favorite').textContent = bookFavorite;
        bookDetailsContainer.querySelector('.books-contents-name').textContent = bookTitle;
        bookDetailsContainer.querySelector('.books-contents-image').innerHTML = `<img src="${bookImage}" class="image">`;
        bookDetailsContainer.querySelector('.books-contents-author').textContent = bookAuthor;
        bookDetailsContainer.querySelector('.books-contents-ratings').textContent = bookRating;
        bookDetailsContainer.querySelector('.books-contents-user-ratings').textContent = bookUserRating;
        bookDetailsContainer.querySelector('.ratings-number').textContent = bookRating;

        // Display the book-details container
        bookDetailsContainer.style.display = 'flex';

        // Open the modal
        openModal();  // Call the openModal function here

        // Handle Borrow Button and Tooltip (as per your code logic)
        const borrowButton = bookDetailsContainer.querySelector('.button-borrow');
        const tooltip = bookDetailsContainer.querySelector('.tooltiptexts');

        if (bookStatus === 'Unavailable' && bookCategory.toLowerCase() === 'circulation' && !bookBorrowStatus) {
            borrowButton.disabled = true;
            tooltip.textContent = 'Unavailable to borrow because it has been borrowed by someone else.';
            tooltip.style.display = 'flex';
        } else if (bookCategory.toLowerCase() === 'circulation' && bookBorrowStatus === 'pending') {
            borrowButton.disabled = true;
            tooltip.textContent = 'You have already requested to borrow this book. You can now claim it at the library.';
            tooltip.style.display = 'flex';
        } else if (bookCategory.toLowerCase() === 'circulation' && bookBorrowStatus === 'borrowed') {
            borrowButton.disabled = true;
            tooltip.textContent = 'You are still borrowing this book. Please return it on time.';
            tooltip.style.display = 'flex';
        } else if (bookStatus === 'Available' && bookCategory.toLowerCase() !== 'circulation' && !bookBorrowStatus) {
            borrowButton.disabled = true;
            tooltip.textContent = 'Only books from the Circulation Section can be borrowed, but you can still read this book in the library.';
            tooltip.style.display = 'flex';
        } else {
            borrowButton.disabled = false;
            tooltip.style.display = 'none';
        }

        // Handle Favorite Button and Tooltip (as per your code logic)
        const favoriteButton = bookDetailsContainer.querySelector('.button-bookmark');
        const favoriteButtonRed = bookDetailsContainer.querySelector('.button-bookmark-red');
        const tooltipAdd = bookDetailsContainer.querySelector('#tooltip-add');
        const tooltipRemove = bookDetailsContainer.querySelector('#tooltip-remove');

        if (bookFavorite && bookFavorite !== 'Remove') {
            favoriteButton.style.display = 'none';
            favoriteButtonRed.style.display = 'flex';
            tooltipAdd.style.display = 'none';
            tooltipRemove.style.display = 'flex';
        } else {
            favoriteButton.style.display = 'flex';
            favoriteButtonRed.style.display = 'none';
            tooltipAdd.style.display = 'flex';
            tooltipRemove.style.display = 'none';
        }

        // Handle Rating Button and Tooltip (as per your code logic)
        const ratingButton = bookDetailsContainer.querySelector('.button-ratings');
        const ratingButtonYellow = bookDetailsContainer.querySelector('.button-ratings-yellow');
        const tooltipAddRatings = bookDetailsContainer.querySelector('#tooltip-add-ratings');
        const tooltipUpdateRatings = bookDetailsContainer.querySelector('#tooltip-update-ratings');

        if (bookUserRating) {
            ratingButton.style.display = 'none';
            ratingButtonYellow.style.display = 'flex';
            tooltipAddRatings.style.display = 'none';
            tooltipUpdateRatings.style.display = 'flex';
        } else {
            ratingButton.style.display = 'flex';
            ratingButtonYellow.style.display = 'none';
            tooltipAddRatings.style.display = 'flex';
            tooltipUpdateRatings.style.display = 'none';
        }

        // Handle star ratings display
        const stars = bookDetailsContainer.querySelectorAll('.star');
        let rating = Math.round(parseFloat(bookRating)) || 0;

        stars.forEach(star => {
            const value = parseFloat(star.getAttribute('data-value'));
            star.classList.toggle('active', value <= rating);
        });
    });
});

// Close button functionality
const closeButton = document.querySelector('.button-close');
closeButton.addEventListener('click', () => {
    bookDetailsContainer.style.display = 'none';  // Hide the book details container
    closeModal();  // Call the closeModal function to close the modal and re-enable scrolling
});
