<a href="dashboard.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/dashboard-white.png" class="image" id="sidebar-dashboard-image">
        </div>
        <div class="font-14px">
            Dashboard
        </div>
    </div>
</a>


<a href="transactions.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/transaction-white.png" class="image" id="sidebar-transaction-image">
        </div>
        <div class="font-14px">
            Transactions
        </div>
    </div>
</a>


<div>
    <div class="sidebar-items" id="books">
        <div class="row row-between">
            <div class="row">
                <div class="sidebar-image">
                    <img src="../images/book-white.png" class="image" id="sidebar-books-image">
                </div>
                <div class="font-14px">
                    Books
                </div>
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
        <a href="category.php">
            <div class="font-14px">
                Category
            </div>
        </a>
        <a href="author.php">
            <div class="font-14px">
                Author
            </div>
        </a>
        <a href="book-list.php">
            <div class="font-14px">
                Book List
            </div>
        </a>
        <a href="missing.php">
            <div class="font-14px">
                Missing
            </div>
        </a>
        <a href="condemned.php">
            <div class="font-14px">
                Condemned
            </div>
        </a>
    </div>
</div>


<a href="application.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/application-white.png" class="image" id="sidebar-application-image">
        </div>
        <div class="font-14px">
            Application
        </div>
    </div>
</a>


<a href="patrons.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/patrons-white.png" class="image" id="sidebar-patrons-image">
        </div>
        <div class="font-14px">
            Patrons
        </div>
    </div>
</a>


<a href="library_id.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/id-white.png" class="image" id="sidebar-id-image">
        </div>
        <div class="font-14px">
            Library ID
        </div>
    </div>
</a>


<a href="delinquent.php">
    <div class="sidebar-items">
        <div class="sidebar-image">
            <img src="../images/ban-white.png" class="image" id="sidebar-delinquent-image">
        </div>
        <div class="font-14px">
            Delinquent
        </div>
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
            <div class="font-14px">
                Librarians
            </div>
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
                    <div class="font-14px">
                        Content Management
                    </div>
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
            <a href="about.php">
                <div class="font-14px">
                    About
                </div>
            </a>
            <a href="news.php">
                <div class="font-14px">
                    News
                </div>
            </a>
            <a href="contact.php">
                <div class="font-14px">
                    Contact
                </div>
            </a>
        </div>
    </div>




    <a href="audit.php">
        <div class="sidebar-items">
            <div class="sidebar-image">
                <img src="../images/logs-white.png" class="image" id="sidebar-logs-image">
            </div>
            <div class="font-14px">
                Audit
            </div>
        </div>
    </a>




    <a href="reports.php">
        <div class="sidebar-items">
            <div class="sidebar-image">
                <img src="../images/reports-white.png" class="image" id="sidebar-reports-image">
            </div>
            <div class="font-14px">
                Reports
            </div>
        </div>
    </a>

<?php endif; ?>

<br>

<a href="logout.php" class="sidebar-items-bottom">
    <div class="row">
        <div class="sidebar-image">
            <img src="../images/logout-white.png" class="image" id="sidebar-logout-image">
        </div>
        <div class="font-14px">
            Logout
        </div>
    </div>
</a>




<script src="js/sidebar.js"></script>