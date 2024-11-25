//sidebar dropdown
document.getElementById('books').addEventListener('click', function () {
    var dropdown = document.querySelector('.sidebar-dropdown-content.books');
    var expandArrow = document.getElementById('sidebar-expand-arrow-books');
    var collapseArrow = document.getElementById('sidebar-collapse-arrow-books');

    if (dropdown.style.display === 'block') {
        dropdown.style.opacity = '0';
        setTimeout(function () {
            dropdown.style.display = 'none';
        }, 300);
        expandArrow.style.display = 'block';
        collapseArrow.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
        setTimeout(function () {
            dropdown.style.opacity = '1';
        }, 10);
        expandArrow.style.display = 'none';
        collapseArrow.style.display = 'block';
    }
});


document.getElementById('content').addEventListener('click', function () {
    var dropdown = document.querySelector('.sidebar-dropdown-content.content');
    var expandArrow = document.getElementById('sidebar-expand-arrow-content');
    var collapseArrow = document.getElementById('sidebar-collapse-arrow-content');

    if (dropdown.style.display === 'block') {
        dropdown.style.opacity = '0';
        setTimeout(function () {
            dropdown.style.display = 'none';
        }, 300);
        expandArrow.style.display = 'block';
        collapseArrow.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
        setTimeout(function () {
            dropdown.style.opacity = '1';
        }, 10);
        expandArrow.style.display = 'none';
        collapseArrow.style.display = 'block';
    }
});