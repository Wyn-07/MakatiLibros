<a href="dashboard.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/dashboard-white.png" class="image" id="sidebar-dashboard-image">
        </div>
        Dashboard
    </div>
</a>


<a href="transactions.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/transaction-white.png" class="image" id="sidebar-transaction-image">
        </div>
        Transactions
    </div>
</a>


<div>
    <div class="sidebar-items" id="books">
        <div class="row row-between">
            <div class="row">
                <div class="sidebar-image">
                    <img src="../images/book-white.png" class="image" id="sidebar-books-image">
                </div>
                Books
            </div>
            <div class="sidebar-dropdown-image">
                <img src="../images/expand-arrow-white.png" class="image"
                    id="sidebar-expand-arrow-books">
                <img src="../images/collapse-arrow-white.png" class="image"
                    id="sidebar-collapse-arrow-books" style="display: none;">
            </div>
        </div>
    </div>

    <div class="sidebar-dropdown-content books">
        <a href="category.php">Category</a>
        <a href="author.php">Author</a>
        <a href="book-list.php">Book List</a>
        <a href="missing.php">Missing</a>
        <a href="condemned.php">Condemned</a>
    </div>
</div>


<a href="patrons.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/patrons-white.png" class="image" id="sidebar-patrons-image">
        </div>
        Patrons
    </div>
</a>


<a href="library_id.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/id-white.png" class="image" id="sidebar-id-image">
        </div>
        Library ID
    </div>
</a>


<a href="delinquent.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/ban-white.png" class="image" id="sidebar-delinquent-image">
        </div>
        Delinquent
    </div>
</a>


<?php
// Assuming $isAdmin is already set as per previous code
if ($isAdmin): ?>
    <a href="librarian.php">
        <div class="sidebar-items">
            <div class="sidebar-image">
                <img src="../images/librarian-white.png" class="image" id="sidebar-librarian-image">
            </div>
            Librarians
        </div>
    </a>
<?php endif; ?>


<?php if ($isAdmin): ?>

    <div>
        <div class="sidebar-items" id="content">
            <div class="row row-between">
                <div class="row">
                    <div class="sidebar-image">
                        <img src="../images/content-white.png" class="image" id="sidebar-content-image">
                    </div>
                    Content
                </div>
                <div class="sidebar-dropdown-image">
                    <img src="../images/expand-arrow-white.png" class="image"
                        id="sidebar-expand-arrow-content">
                    <img src="../images/collapse-arrow-white.png" class="image"
                        id="sidebar-collapse-arrow-content" style="display: none;">
                </div>
            </div>
        </div>

        <div class="sidebar-dropdown-content content">
            <a href="about.php">About</a>
            <a href="news.php">News</a>
            <a href="contact.php">Contact</a>
        </div>
    </div>




    <a href="logs.php">
        <div class="sidebar-items">
            <div class="sidebar-image">
                <img src="../images/logs-white.png" class="image" id="sidebar-logs-image">
            </div>
            Logs
        </div>
    </a>




    <a href="reports.php">
        <div class="sidebar-items">
            <div class="sidebar-image">
                <img src="../images/reports-white.png" class="image" id="sidebar-reports-image">
            </div>
            Reports
        </div>
    </a>

<?php endif; ?>

<br>

<a href="logout.php" class="sidebar-items-bottom">
    <div class="row">
        <div class="sidebar-image">
            <img src="../images/logout-white.png" class="image" id="sidebar-logout-image">
        </div>
        Logout
    </div>
</a>




<script src="js/sidebar.js"></script>