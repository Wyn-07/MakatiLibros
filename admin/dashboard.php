<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <div class="container-top">

            <div class="row row-between">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="../images/makati-logo.png" class="image">
                    </div>
                    MakatiLibros
                </div>


                <div class="row-auto container-profile" onclick="openEditModal()">
                    <div class="container-round profile">
                        <img src="../images/sample-profile.jpg" class="image">
                    </div>
                    Wyn Bacolod
                </div>

            </div>

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

                <div class="row-padding-20px no-wrap">

                    <div class="container-status bg-books">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    4
                                </div>
                                <div class="count-description">
                                    Total Books
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/books-white.png" class="image">
                            </div>
                        </div>
                        <div class="container-status-more">
                            More Info
                            <img src="../images/next-white.png">
                        </div>
                    </div>

                    <div class="container-status bg-patrons">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    4
                                </div>
                                <div class="count-description">
                                    Total Readers
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/readers-white.png" class="image">
                            </div>
                        </div>
                        <div class="container-status-more">
                            More Info
                            <img src="../images/next-white.png">
                        </div>
                    </div>

                    <div class="container-status bg-borrowed">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    4
                                </div>
                                <div class="count-description">
                                    Borrowed Today
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/borrow-books-white.png" class="image">
                            </div>
                        </div>
                        <div class="container-status-more">
                            More Info
                            <img src="../images/next-white.png">
                        </div>
                    </div>

                    <div class="container-status bg-returned">
                        <div class="row no-wrap">
                            <div class="status-count">
                                <div class="count-number">
                                    4
                                </div>
                                <div class="count-description">
                                    Returned Today
                                </div>
                            </div>
                            <div class="status-image">
                                <img src="../images/return-books-white.png" class="image">
                            </div>
                        </div>
                        <div class="container-status-more">
                            More Info
                            <img src="../images/next-white.png">
                        </div>
                    </div>
                </div>


                <div class="row-padding-20px">
                    <div class="container-bar">
                        <div class="row row-between">
                            <div class="chart-title">Monthly Transaction Report</div>
                            <div class="chart-title">
                                Select Year:
                                <select class="chart-select">
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>

                        </div>
                        <canvas id="barChart"></canvas>
                    </div>

                    <div class="container-pie">
                        <div class="chart-title">Readers Age Category</div>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    const ctx = document.getElementById('barChart');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Borrow',
                                data: [12, 15, 10, 8, 20, 18, 25, 22, 30, 28, 35, 40],
                                backgroundColor: '#BFFF00',
                                borderWidth: 1
                            }, {
                                label: 'Return',
                                data: [19, 17, 22, 24, 18, 21, 15, 19, 14, 17, 13, 10],
                                backgroundColor: '#FFFFE0',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
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
                                        display: false
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
                </script>


                <script>
                    const pie = document.getElementById('pieChart');

                    new Chart(pie, {
                        type: 'pie',
                        data: {
                            labels: ['Child', 'Teenager', 'Adult', 'Senior'],
                            datasets: [{
                                label: 'Readers',
                                data: [12, 20, 30, 40],
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
                </script>





            </div>

        </div>










        <div id="editModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Edit | Profile
                    </div>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="input-text" autocomplete="off" required>

                            <div class="profile-image">
                                <img src="" alt="">
                            </div>
                        </div>


                        <div class="container-input">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>



    </div>
</body>

</html>


<script>
    //sidebar dropdown
    document.getElementById('transaction').addEventListener('click', function() {
        var dropdown = document.querySelector('.sidebar-dropdown-content.transaction');
        var expandArrow = document.getElementById('sidebar-expand-arrow-transaction');
        var collapseArrow = document.getElementById('sidebar-collapse-arrow-transaction');

        if (dropdown.style.display === 'block') {
            dropdown.style.opacity = '0';
            setTimeout(function() {
                dropdown.style.display = 'none';
            }, 300);
            expandArrow.style.display = 'block';
            collapseArrow.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
            setTimeout(function() {
                dropdown.style.opacity = '1';
            }, 10);
            expandArrow.style.display = 'none';
            collapseArrow.style.display = 'block';
        }
    });


    document.getElementById('books').addEventListener('click', function() {
        var dropdown = document.querySelector('.sidebar-dropdown-content.books');
        var expandArrow = document.getElementById('sidebar-expand-arrow-books');
        var collapseArrow = document.getElementById('sidebar-collapse-arrow-books');

        if (dropdown.style.display === 'block') {
            dropdown.style.opacity = '0';
            setTimeout(function() {
                dropdown.style.display = 'none';
            }, 300);
            expandArrow.style.display = 'block';
            collapseArrow.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
            setTimeout(function() {
                dropdown.style.opacity = '1';
            }, 10);
            expandArrow.style.display = 'none';
            collapseArrow.style.display = 'block';
        }
    });
</script>


<script src="js/edit-modal-profile.js"></script>