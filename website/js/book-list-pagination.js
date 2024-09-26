document.addEventListener('DOMContentLoaded', function() {
    const bookContainer = document.getElementById('bookContainer');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');
    const searchInput = document.querySelector('.search'); // Ensure the search input selector is correct

    let itemsPerPage = parseInt(itemsPerPageSelect.value);
    let currentPage = 1;

    // Store original book data
    let originalBooks = Array.from(bookContainer.children).map(bookDiv => ({
        name: bookDiv.querySelector('.books-name-2').textContent,
        image: bookDiv.querySelector('.books-image-2 img').src || 'book-image-placeholder.png' // Get the src of the img tag
    }));
    let filteredBooks = [...originalBooks];

    function updateDisplay() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedBooks = filteredBooks.slice(start, end);

        // Clear current display
        bookContainer.innerHTML = '';

        if (paginatedBooks.length === 0) {
            bookContainer.innerHTML = `
            <div class="container-unavailable">
                <div class="unavailable-image">
                    <img src="../images/no-books.png" class="image">
                </div>
                <div class="unavailable-text">Not Found</div>
            </div>
        `;
        } else {
            paginatedBooks.forEach(book => {
                const bookDiv = document.createElement('div');
                bookDiv.classList.add('container-books-2');
                bookDiv.innerHTML = `
                <div class="books-image-2">
                    <img src="${book.image}" class="image">
                </div>
                <div class="books-name-2">${book.name}</div>
            `;
                bookContainer.appendChild(bookDiv);
            });
        }

        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages;
    }

    // Event listeners for pagination controls
    itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        updateDisplay();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }); // Scroll to top
    });

    prevPageButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updateDisplay();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            }); // Scroll to top
        }
    });

    nextPageButton.addEventListener('click', function() {
        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updateDisplay();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            }); // Scroll to top
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();

        if (query) {
            filteredBooks = originalBooks.filter(book => book.name.toLowerCase().includes(query));
        } else {
            filteredBooks = [...originalBooks];
        }

        currentPage = 1;
        updateDisplay();
    });

    updateDisplay();
});









// const books = [];
// for (let i = 1; i <= 50; i++) {
//     books.push({ name: `Book ${i}`, image: 'book-image-placeholder.png' });
// }

// let currentPage = 1;
// let itemsPerPage = 20;
// let filteredBooks = books;

// function renderBooks() {
//     const bookContainer = document.getElementById('bookContainer');
//     const noBooksDiv = document.querySelector('.row.row-center'); // Select the div to be hidden
//     bookContainer.innerHTML = '';
    
//     // Check if no books found
//     const start = (currentPage - 1) * itemsPerPage;
//     const end = start + itemsPerPage;
//     const paginatedBooks = filteredBooks.slice(start, end);

//     if (paginatedBooks.length === 0) {
//         // If no books found, display the "Not Found" message and hide the row
//         const notFoundDiv = document.createElement('div');
//         notFoundDiv.classList.add('container-unavailable');
//         notFoundDiv.innerHTML = `
//             <div class="unavailable-image">
//                 <img src="../images/no-books.png" class="image">
//             </div>
//             <div class="unavailable-text">
//                 Not Found
//             </div>
//         `;
//         bookContainer.appendChild(notFoundDiv);

//         // Hide the row
//         if (noBooksDiv) {
//             noBooksDiv.style.display = 'none';
//         }
//     } else {
//         // Otherwise, render the books and show the row
//         paginatedBooks.forEach(book => {
//             const bookDiv = document.createElement('div');
//             bookDiv.classList.add('container-books-2');
//             bookDiv.innerHTML = `
//                 <div class="books-image-2" style="background-image: url('${book.image}')"></div>
//                 <div class="books-name">${book.name}</div>
//             `;
//             bookContainer.appendChild(bookDiv);
//         });

//         // Show the row
//         if (noBooksDiv) {
//             noBooksDiv.style.display = '';
//         }
//     }

//     document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${Math.ceil(filteredBooks.length / itemsPerPage)}`;

//     document.getElementById('prevPage').disabled = currentPage === 1;
//     document.getElementById('nextPage').disabled = currentPage === Math.ceil(filteredBooks.length / itemsPerPage);
// }


// function setupEventListeners() {
//     document.getElementById('itemsPerPage').addEventListener('change', function () {
//         itemsPerPage = parseInt(this.value);
//         currentPage = 1;
//         renderBooks();
//     });

//     document.getElementById('prevPage').addEventListener('click', function () {
//         if (currentPage > 1) {
//             currentPage--;
//             renderBooks();
//             window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to the top smoothly
//         }
//     });

//     document.getElementById('nextPage').addEventListener('click', function () {
//         if (currentPage < Math.ceil(filteredBooks.length / itemsPerPage)) {
//             currentPage++;
//             renderBooks();
//             window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to the top smoothly
//         }
//     });

//     document.getElementById('itemsPerPage').addEventListener('change', function () {
//         itemsPerPage = parseInt(this.value);
//         currentPage = 1;
//         renderBooks();

//         // Scroll to the last item
//         const bookContainer = document.getElementById('bookContainer');
//         const lastBook = bookContainer.lastElementChild;

//         if (lastBook) {
//             lastBook.scrollIntoView({ behavior: 'smooth' }); // Smoothly scroll to the last item
//         }
//     });


//     document.querySelector('.search').addEventListener('input', function () {
//         const query = this.value.toLowerCase();
//         filteredBooks = books.filter(book => book.name.toLowerCase().includes(query));
//         currentPage = 1;
//         renderBooks();
//     });
// }

// document.addEventListener('DOMContentLoaded', function () {
//     renderBooks();
//     setupEventListeners();
// });