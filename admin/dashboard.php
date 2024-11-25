<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>


<?php
session_start();

include '../connection.php';

include 'functions/count_status.php';

?>


<body>
    <div class="wrapper">

        <div class="container-top">

            <?php include 'container-top.php'; ?>

        </div>

        <div class="container-content">

            <div class="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>

            <div class="body">
                <div class="row">
                    <div class="title-26px">
                        Dashboard
                    </div>
                </div>

                <div class="container-success" id="container-success" style="display: <?php echo isset($_SESSION['success_display']) ? $_SESSION['success_display'] : 'none';
                                                                                        unset($_SESSION['success_display']); ?>;">
                    <div class="container-success-description">
                        <?php if (isset($_SESSION['success_message'])) {
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                        } ?>
                    </div>
                    <button type="button" class="button-success-close" onclick="closeSuccessStatus()">&times;</button>

                </div>

                <div class="row-padding-20px no-wrap">


                    <div class="container-status bg-books">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    <?php echo $result['total_books'] ?>
                                </div>
                                <div class="count-description">
                                    Total Books
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/books-white.png" class="image">
                            </div>
                        </div>
                        <a href="book-list.php">
                            <div class="container-status-more">
                                More Info
                                <img src="../images/next-white.png">
                            </div>
                        </a>
                    </div>

                    <div class="container-status bg-patrons">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    <?php echo $result['total_patrons'] ?>
                                </div>
                                <div class="count-description">
                                    Total Patrons
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/patrons-white.png" class="image">
                            </div>
                        </div>
                        <a href="patrons.php">
                            <div class="container-status-more">
                                More Info
                                <img src="../images/next-white.png">
                            </div>
                        </a>
                    </div>

                    <div class="container-status bg-borrowed">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    <?php echo $result['borrowed_today'] ?>
                                </div>
                                <div class="count-description">
                                    Borrowed Today
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/borrow-books-white.png" class="image">
                            </div>
                        </div>
                        <a href="borrow.php">
                            <div class="container-status-more">
                                More Info
                                <img src="../images/next-white.png">
                            </div>
                        </a>
                    </div>

                    <div class="container-status bg-returned">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    <?php echo $result['returned_today'] ?>
                                </div>
                                <div class="count-description">
                                    Returned Today
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/return-books-white.png" class="image">
                            </div>
                        </div>
                        <a href="return.php">
                            <div class="container-status-more">
                                More Info
                                <img src="../images/next-white.png">
                            </div>
                        </a>
                    </div>
                </div>


                <div class="row-padding-20px">

                    <?php $containerClass = $isAdmin ? 'container-bar' : 'container-bar full-width'; ?>

                    <div class="<?php echo $containerClass; ?>">
                        <div class="row row-between">
                            <div class="chart-title">Monthly Transaction Report</div>
                            <div class="chart-title">
                                Select Year:
                                <select id="yearSelect" class="chart-select">
                                    <option value="2023">2023</option>
                                    <option value="2024" selected>2024</option>
                                </select>
                            </div>

                        </div>
                        <canvas id="barChart"></canvas>
                    </div>

                    <?php if ($isAdmin): ?>

                        <div class="container-pie">
                            <div class="chart-title">Patrons Age Category</div>
                            <canvas id="pieChart"></canvas>
                        </div>

                    <?php endif; ?>

                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const yearSelect = document.getElementById("yearSelect");
                        const barChartCanvas = document.getElementById("barChart").getContext('2d');
                        let barChart;

                        // Function to fetch and display data based on the selected year
                        function fetchDataForYear(year) {
                            fetch(`functions/fetch_borrow_data.php?year=${year}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (barChart) {
                                        barChart.destroy(); // Destroy the old chart before creating a new one
                                    }

                                    // Assuming 'data' has labels, borrowed, and returned counts
                                    barChart = new Chart(barChartCanvas, {
                                        type: 'bar',
                                        data: {
                                            labels: data.labels, // E.g., ["January", "February", ...]
                                            datasets: [{
                                                    label: `Borrowed in ${year}`,
                                                    data: data.borrowed, // E.g., [10, 20, 30, ...]
                                                    backgroundColor: '#BFFF00',
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: `Returned in ${year}`,
                                                    data: data.returned, // E.g., [5, 15, 20, ...]
                                                    backgroundColor: '#FFFFE0',
                                                    borderWidth: 1
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            scales: {
                                                x: {
                                                    grid: {
                                                        display: true
                                                    },
                                                    ticks: {
                                                        color: 'white',
                                                        font: {
                                                            size: 16,
                                                            family: 'Poppins'
                                                        }
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    grid: {
                                                        display: true
                                                    },
                                                    ticks: {
                                                        color: 'white',
                                                        font: {
                                                            size: 16,
                                                            family: 'Poppins'
                                                        }
                                                    }
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    labels: {
                                                        color: 'white',
                                                        font: {
                                                            size: 16,
                                                            family: 'Poppins'
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                })
                                .catch(error => {
                                    console.error("Error fetching data:", error);
                                });
                        }

                        // Fetch data for the default year on page load
                        fetchDataForYear(yearSelect.value);

                        // Update data when a new year is selected
                        yearSelect.addEventListener("change", function() {
                            const selectedYear = yearSelect.value;
                            fetchDataForYear(selectedYear);
                        });
                    });
                </script>




                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const pieChartCanvas = document.getElementById('pieChart').getContext('2d');

                        fetch('functions/count_patrons_ages.php') // Update with the correct path to your PHP file
                            .then(response => response.json())
                            .then(data => {
                                const pie = new Chart(pieChartCanvas, {
                                    type: 'pie',
                                    data: {
                                        labels: ['Child', 'Teenager', 'Adult', 'Senior'],
                                        datasets: [{
                                            label: 'Patrons',
                                            data: [data.Child, data.Teenager, data.Adult, data.Senior],
                                            backgroundColor: ['#FFB6C1', '#FFDAB9', '#87CEEB', '#FFD700'],
                                            borderWidth: 0
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                position: 'bottom',
                                                labels: {
                                                    color: 'white',
                                                    font: {
                                                        size: 14,
                                                        family: 'Poppins'
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            })
                            .catch(error => {
                                console.error("Error fetching data:", error);
                            });
                    });
                </script>



            </div>

        </div>





    </div>
</body>

</html>





<script src="js/close-status.js"></script>