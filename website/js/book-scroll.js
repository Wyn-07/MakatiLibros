document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.row-books-container').forEach(container => {
        const rowBooks = container.querySelector('.row-books');
        const arrowLeft = container.querySelector('.arrow-left');
        const arrowRight = container.querySelector('.arrow-right');
        const viewMore = container.closest('.contents, .contents-big-padding').querySelector('.button-view-more');

        function checkOverflow() {
            if (rowBooks.scrollWidth > rowBooks.clientWidth) {
                arrowLeft.style.display = 'flex';
                arrowRight.style.display = 'flex';
                viewMore.style.display = 'block';
            } else {
                arrowLeft.style.display = 'none';
                arrowRight.style.display = 'none';
                viewMore.style.display = 'none';
            }
        }

        // Check overflow on page load
        checkOverflow();

        // Optionally, check overflow on window resize
        window.addEventListener('resize', checkOverflow);

        // Scroll functionality
        arrowLeft.addEventListener('click', () => {
            rowBooks.scrollBy({ left: -200, behavior: 'smooth' });
        });

        arrowRight.addEventListener('click', () => {
            rowBooks.scrollBy({ left: 200, behavior: 'smooth' });
        });
    });
});
