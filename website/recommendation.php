<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recommendation</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<?php
session_start();

// Get the user ID from the session
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 3; // Default to 3 if not set

// Define the path to the Python script
$pythonScript = 'kmeans_recommender.py';

// Execute the Python script and capture both standard output and error output
$output = shell_exec("py $pythonScript $userId 2>&1");

// Display the raw output for debugging purposes
// echo "<h3>Python Script Output:</h3>";
// echo "<pre>" . htmlspecialchars($output) . "</pre>";

// Decode JSON response from Python script
$recommendations = json_decode($output, true);

// Check if the JSON was decoded properly
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p>JSON decoding error: " . htmlspecialchars(json_last_error_msg()) . "</p>";
    $recommendations = [];
}
?>

<body>
    <div class="wrapper">


        <div class="container-top">
            <?php include 'navbar.php'; ?>
        </div>



        <div id="overlay" class="overlay"></div>


        <div class="row-body">

            <div class="container-sidebar" id="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="container-content">

                <div class="row row-between">

                    <div class="contents-title">
                        Recommendation
                    </div>

                    <div class="container-search row">
                        <input type="text" class="search">

                        <div class="container-search-image" onclick="goToResults()">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="container-filter">

                        <div class="row auto">
                            <div class="filter-content-image">
                                <img src="../images/filter-black.png" class="image">
                            </div>
                            <div class="filter-content-title">Search Filters</div>
                        </div>

                        <div class="filter-content">

                            <div class="filter-container-item">
                                <div class="filter-title">By Category</div>

                                <div class="filter-item">
                                    <input type="checkbox" name="filipiniana" value="filipiniana">
                                    <label for="filipiniana"> Filipiniana </label>
                                </div>

                                <div class="filter-item">
                                    <input type="checkbox" name="academic-books" value="academic-books">
                                    <label for="academic-books"> Academic Books </label>
                                </div>

                                <div class="filter-item">
                                    <input type="checkbox" name="children-books" value="children-books">
                                    <label for="children-books"> Children's Books </label>
                                </div>


                                <div class="filter-item">
                                    <input type="checkbox" name="special-collection" value="special-collection">
                                    <label for="special-collection"> Special Collection </label>
                                </div>

                                <div class="filter-item">
                                    <input type="checkbox" name="government-publications"
                                        value="government-publications">
                                    <label for="government-publications"> Government Publications </label>
                                </div>
                            </div>

                            <div class="filter-container-item">
                                <div class="filter-title">By Date</div>
                                <input type="date" class="filter-date">
                            </div>

                            <div class="button button-clear">Clear All</div>
                        </div>

                    </div>

                    <div class="container-contents-body">

                        <div class="row-contents-center" id="bookContainer">
                            <?php if (is_array($recommendations) && !empty($recommendations)): ?>
                                <?php foreach ($recommendations as $book): ?>
                                    <div class="container-books-2">
                                        <div class="books-image-2">
                                            <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                        </div>
                                        <div class="books-name-2"><?php echo htmlspecialchars($book['title']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No recommendations available.</p>
                            <?php endif; ?>
                        </div>


                        <div class="row row-center">
                            <div class="pagination-controls">
                                Items per page:
                                <select class="page-select" id="itemsPerPage">
                                    <option value="20">20</option>
                                    <option value="40">40</option>
                                    <option value="60">60</option>
                                </select>
                            </div>

                            <div class="pagination-controls">
                                <button class="button button-page" id="prevPage">Previous</button>
                                <span class="page-number" id="pageInfo"></span>
                                <button class="button button-page" id="nextPage">Next</button>
                            </div>
                        </div>

                    </div>




                </div>

            </div>

        </div>





        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>


    </div>
</body>



</html>

<script src="js/sidebar.js"></script>
<script src="js/book-list-pagination.js"></script>

<script>

</script>