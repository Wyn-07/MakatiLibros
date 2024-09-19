document.getElementById('menuButton').addEventListener('click', function () {
    toggleSidebar();
});

document.getElementById('overlay').addEventListener('click', function () {
    var sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
    }

    var overlay = document.getElementById('overlay');
    if (overlay.classList.contains('show-overlay')) {
        overlay.classList.remove('show-overlay');
    }

    // Remove no-scroll class when sidebar is hidden
    document.body.classList.remove('no-scroll');
});

function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');

    var overlay = document.getElementById('overlay');
    overlay.classList.toggle('show-overlay');

    // Toggle no-scroll class based on sidebar visibility
    document.body.classList.toggle('no-scroll', sidebar.classList.contains('show'));
}