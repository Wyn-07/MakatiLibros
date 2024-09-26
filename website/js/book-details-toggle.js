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
        const bookCategory = book.querySelector('.books-category').textContent;
        const bookBorrowStatus = book.querySelector('.books-borrow-status').textContent;
        const bookFavorite = book.querySelector('.books-favorite').textContent;

        const bookTitle = book.querySelector('.books-name').textContent;
        const bookImage = book.querySelector('.books-image img').src;
        const bookAuthor = book.querySelector('.books-author').textContent;
        const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';
        const bookUserRating = book.querySelector('.books-user-ratings') ? book.querySelector('.books-user-ratings').textContent : '0';

        // Update the book-details container with the clicked book's information
        const bookDetailsContainer = document.getElementById('book-details');
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



        // Check if bookCategory is not equal to 'Circulation Section'
        if (bookCategory.toLowerCase() !== 'circulation'.toLowerCase()) {
            const borrowButton = bookDetailsContainer.querySelector('.button-borrow');
            const tooltip = bookDetailsContainer.querySelector('.tooltiptexts');

            if (borrowButton) {
                borrowButton.disabled = true;
            }

            if (tooltip) {
                tooltip.style.display = 'flex';
            }
        } else {
            const borrowButton = bookDetailsContainer.querySelector('.button-borrow');
            const tooltip = bookDetailsContainer.querySelector('.tooltiptexts');

            if (bookBorrowStatus.toLowerCase() === 'pending') {
                if (borrowButton) {
                    borrowButton.disabled = true;
                    tooltip.textContent = 'You have already requested to borrow this book. You can now claim it at the library';
                }

                if (tooltip) {
                    tooltip.style.display = 'flex';
                }

            } else if (bookBorrowStatus.toLowerCase() === 'borrowed') {
                if (borrowButton) {
                    borrowButton.disabled = true;
                    tooltip.textContent = 'You are still borrowing the book. Please return it on time.';

                }

                if (tooltip) {
                    tooltip.style.display = 'flex';
                }

            } else {
                if (borrowButton) {
                    borrowButton.disabled = false;
                    borrowButton.textContent = 'Borrow';
                }

                if (tooltip) {
                    tooltip.style.display = 'none';
                }
            }

        }



        
        const favoriteButton = bookDetailsContainer.querySelector('.button-bookmark');
        const favoriteButtonRed = bookDetailsContainer.querySelector('.button-bookmark-red');

        const tooltipAdd = bookDetailsContainer.querySelector('#tooltip-add');
        const tooltipRemove = bookDetailsContainer.querySelector('#tooltip-remove');
        

        if (bookFavorite !== '' && bookFavorite !== 'Remove') {
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





        const ratingButton = bookDetailsContainer.querySelector('.button-ratings');
        const ratingButtonYellow = bookDetailsContainer.querySelector('.button-ratings-yellow');

        const tooltipAddRatings = bookDetailsContainer.querySelector('#tooltip-add-ratings');
        const tooltipUpdateRatings = bookDetailsContainer.querySelector('#tooltip-update-ratings');
        

        if (bookUserRating !== '') {
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
