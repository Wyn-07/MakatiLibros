<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                        Reports
                    </div>
                </div>


                <!-- monthly transaction report -->
                <div class="row-padding-20px">

                    <div class="container-bar-month">
                        <div class="row row-between">
                            <div class="chart-title">Monthly Transaction Report</div>

                            <div class="chart-title">
                                Select Year:
                                <select id="yearSelect" class="chart-select">
                                    <!-- Dynamic options will be populated here -->
                                </select>
                            </div>

                        </div>

                        <canvas id="barChart" style="max-height: 300px;"></canvas>

                    </div>

                </div>


                <!-- year populate dropdown -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const yearSelect = document.getElementById("yearSelect");

                        // Create a Date object to get the current date
                        const currentDate = new Date();

                        // Set the time zone to Asia/Manila (UTC +8)
                        const currentYear = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            year: 'numeric'
                        });

                        // Start year (2023)
                        let startYear = 2023;
                        let endYear = parseInt(currentYear);

                        // Loop through the years to populate the dropdown
                        for (let year = startYear; year <= endYear; year++) {
                            // Create an option element
                            const option = document.createElement("option");
                            option.value = year;
                            option.textContent = year;

                            // Set the default selected option to the current year
                            if (year === parseInt(currentYear)) {
                                option.selected = true; // Mark this as the selected option
                            }

                            // Append the option to the select
                            yearSelect.appendChild(option);
                        }
                    });
                </script>

                <!-- monthly bargraph -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const yearSelect = document.getElementById("yearSelect");
                        const barChartCanvas = document.getElementById("barChart").getContext('2d');
                        let barChart;

                        // Modal selectors
                        const viewModal = document.getElementById("viewModal");
                        const modalContent = viewModal.querySelector("div > div");

                        // Function to close the modal
                        function closeViewModal() {
                            viewModal.classList.remove('show');
                        }

                        document.querySelector(".modal-close").addEventListener("click", closeViewModal);

                        // Function to fetch and display data for a clicked bar
                        function openViewModal(month, type, year) {
                            modalContent.innerHTML = `
                                        <div class="row row-between">
                                            <div class="title-26px">View | Report</div>
                                            <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                        </div>
                                        <div>Loading data...</div>
                                    `;
                            viewModal.classList.add('show');

                            // Fetch details from the backend
                            fetch(`functions/fetch_borrow_details.php?month=${month}&type=${type}&year=${year}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.details.length > 0) {
                                        const tableRows = data.details
                                            .map(detail => `
                                                        <tr>
                                                            <td>${detail.date}</td>
                                                            <td>${detail.bookTitle}</td>
                                                            <td>${detail.patron}</td>
                                                        </tr>
                                                    `)
                                            .join("");

                                        modalContent.innerHTML = `
                                            <div class="row row-between">
                                                <div class="title-26px">View | Report</div>
                                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Book Title</th>
                                                        <th>Patron</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${tableRows}
                                                </tbody>
                                            </table>
                                        `;
                                    } else {
                                        modalContent.innerHTML = `
                                            <div class="row row-between">
                                                <div class="title-26px">View | Report</div>
                                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                            </div>
                                            <div>No data available for this selection.</div>
                                        `;
                                    }
                                })
                                .catch(error => {
                                    console.error("Error fetching details:", error);
                                    modalContent.innerHTML = `
                                        <div class="row row-between">
                                            <div class="title-26px">View | Report</div>
                                            <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                        </div>
                                        <div>Error loading data. Please try again later.</div>
                                    `;
                                });
                        }

                        // Function to fetch and display data for a given year
                        function fetchDataForYear(year) {
                            fetch(`functions/fetch_borrow_data.php?year=${year}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (barChart) {
                                        barChart.destroy();
                                    }

                                    barChart = new Chart(barChartCanvas, {
                                        type: 'bar',
                                        data: {
                                            labels: data.labels, // E.g., ["January", "February", ...]
                                            datasets: [{
                                                    label: `Borrowed in ${year}`,
                                                    data: data.borrowed,
                                                    backgroundColor: '#BFFF00',
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: `Returned in ${year}`,
                                                    data: data.returned,
                                                    backgroundColor: '#FFFFE0',
                                                    borderWidth: 1
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            onClick: (event, elements) => {
                                                if (elements.length > 0) {
                                                    const element = elements[0];
                                                    const datasetIndex = element.datasetIndex;
                                                    const dataIndex = element.index;

                                                    // Extract details from the clicked bar
                                                    const type = barChart.data.datasets[datasetIndex].label.includes("Borrowed") ?
                                                        "borrowed" :
                                                        "returned"; // Determine type
                                                    const month = barChart.data.labels[dataIndex]; // Month

                                                    // Populate the modal with extracted data
                                                    openViewModal(month, type, year);
                                                }
                                            },
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
                            fetchDataForYear(yearSelect.value);
                        });
                    });
                </script>





                <!-- weekly transaction report -->
                <div class="row-padding-20px">
                    <div class="container-bar full-width">
                        <div class="row row-between">
                            <div class="chart-title">Weekly Transaction Report</div>
                            <div class="chart-title">
                                Select Month:
                                <select id="monthWeekSelect" class="chart-select">
                                    <!-- Dynamic options will be populated here -->
                                </select>
                            </div>
                        </div>
                        <canvas id="barChartWeek" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                <!-- month populate dropdown -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const monthSelect = document.getElementById("monthWeekSelect");

                        // Create a Date object to get the current date
                        const currentDate = new Date();
                        const currentYear = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            year: 'numeric'
                        });
                        const currentMonth = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            month: '2-digit'
                        });

                        let startYear = 2023;
                        let endYear = parseInt(currentYear);
                        let endMonth = parseInt(currentMonth);

                        // Loop through years and months to populate the dropdown
                        for (let year = startYear; year <= endYear; year++) {
                            let startMonth = year === startYear ? 1 : 1;
                            let endLoopMonth = year === endYear ? endMonth : 12;

                            for (let month = startMonth; month <= endLoopMonth; month++) {
                                const formattedMonth = year + '-' + String(month).padStart(2, '0');

                                const option = document.createElement("option");
                                option.value = formattedMonth;
                                option.textContent = new Date(formattedMonth + '-01').toLocaleString('en-US', {
                                    timeZone: 'Asia/Manila',
                                    month: 'long',
                                    year: 'numeric'
                                });

                                if (formattedMonth === currentYear + '-' + currentMonth) {
                                    option.selected = true;
                                }

                                monthSelect.appendChild(option);
                            }
                        }
                    });
                </script>

                <!-- weekly bargraph -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const monthSelect = document.getElementById("monthWeekSelect");
                        const barChartCanvas = document.getElementById("barChartWeek").getContext('2d');
                        let barChart;
                        const viewModal = document.getElementById("viewModal");
                        const modalContent = viewModal.querySelector("div > div");

                        // Create a Date object to get the current date
                        const currentDate = new Date();
                        const currentYear = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            year: 'numeric'
                        });
                        const currentMonth = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            month: '2-digit'
                        });

                        let startYear = 2023;
                        let endYear = parseInt(currentYear);
                        let endMonth = parseInt(currentMonth);

                        // Populate the month dropdown with available months from start year to the current month
                        for (let year = startYear; year <= endYear; year++) {
                            let startMonth = year === startYear ? 1 : 1; // Starting from January in the first year
                            let endLoopMonth = year === endYear ? endMonth : 12; // Limit to the current month in the current year

                            for (let month = startMonth; month <= endLoopMonth; month++) {
                                const formattedMonth = year + '-' + String(month).padStart(2, '0');

                                const option = document.createElement("option");
                                option.value = formattedMonth;
                                option.textContent = new Date(formattedMonth + '-01').toLocaleString('en-US', {
                                    timeZone: 'Asia/Manila',
                                    month: 'long',
                                    year: 'numeric'
                                });

                                if (formattedMonth === currentYear + '-' + currentMonth) {
                                    option.selected = true;
                                }

                                monthSelect.appendChild(option);
                            }
                        }

                        // Fetch data and display the bar chart based on the selected month
                        function fetchDataForMonth(month) {
                            const year = month.split('-')[0]; // Extract year from the selected month
                            fetch(`functions/fetch_borrow_data_week.php?month=${month}&year=${year}`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log("Fetched week details:", data); // Log the week details data

                                    if (barChart) {
                                        barChart.destroy(); // Destroy the old chart before creating a new one
                                    }

                                    barChart = new Chart(barChartCanvas, {
                                        type: 'bar',
                                        data: {
                                            labels: data.labels, // Weeks of the month
                                            datasets: [{
                                                    label: `Borrowed in ${month}`,
                                                    data: data.borrowed, // Weekly data for borrowed books
                                                    backgroundColor: '#BFFF00',
                                                    borderWidth: 1,
                                                },
                                                {
                                                    label: `Returned in ${month}`,
                                                    data: data.returned, // Weekly data for returned books
                                                    backgroundColor: '#FFFFE0',
                                                    borderWidth: 1,
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            onClick: (event, elements) => {
                                                if (elements.length > 0) {
                                                    const element = elements[0];
                                                    const datasetIndex = element.datasetIndex;
                                                    const dataIndex = element.index;

                                                    const type = barChart.data.datasets[datasetIndex].label.includes("Borrowed") ? "borrowed" : "returned";
                                                    const week = barChart.data.labels[dataIndex]; // Week

                                                    // Open the modal with the selected week's data
                                                    openViewModal(week, type, month, year);
                                                }
                                            },
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
                                .catch(error => console.error("Error fetching data:", error));
                        }

                        // Open modal with transaction details
                        function openViewModal(week, type, month) {
                            const year = month.split('-')[0]; // Extract year from the month string

                            modalContent.innerHTML = `Loading data...`;
                            viewModal.classList.add('show');

                            // Fetch details for the selected week
                            fetch(`functions/fetch_borrow_details_week.php?week=${week}&type=${type}&year=${year}&month=${month}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.details.length > 0) {
                                        const tableRows = data.details.map(detail => `
                                            <tr>
                                                <td>${detail.date}</td>
                                                <td>${detail.bookTitle}</td>
                                                <td>${detail.patron}</td>
                                            </tr>
                                        `).join("");

                                        modalContent.innerHTML = `
                                            <div class="row row-between">
                                                <div class="title-26px">View | Report</div>
                                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Book Title</th>
                                                        <th>Patron</th>
                                                    </tr>
                                                </thead>
                                                <tbody>${tableRows}</tbody>
                                            </table>
                                        `;
                                    } else {
                                        modalContent.innerHTML = `
                                            <div class="row row-between">
                                                <div class="title-26px">View | Report</div>
                                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                            </div>
                                            <div>No data available for this selection.</div>
                                        `;
                                    }
                                })
                                .catch(error => {
                                    modalContent.innerHTML = `
                                            <div class="row row-between">
                                                <div class="title-26px">View | Report</div>
                                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                                            </div>
                                            <div>Error loading data. Please try again later.</div>
                                        `;
                                });
                        }


                        // Close modal
                        function closeViewModal() {
                            viewModal.classList.remove('show');
                        }

                        // Event listener for month selection change
                        monthSelect.addEventListener("change", function() {
                            fetchDataForMonth(monthSelect.value);
                        });

                        // Fetch data for the default month on page load
                        fetchDataForMonth(monthSelect.value);
                    });
                </script>








                <!-- daily transaction report -->
                <div class="row-padding-20px">
                    <div class="container-bar full-width">
                        <div class="row row-between">
                            <div class="chart-title">Daily Transaction Report</div>
                            <div class="chart-title">
                                Select Month:
                                <select id="monthSelect" class="chart-select">
                                    <!-- Dynamic options will be populated here -->
                                </select>
                            </div>
                        </div>
                        <canvas id="barChartDay" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                <!-- month populate dropdown -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const monthSelect = document.getElementById("monthSelect");

                        const currentDate = new Date();
                        const options = {
                            timeZone: 'Asia/Manila',
                            year: 'numeric',
                            month: '2-digit'
                        };
                        const currentYear = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            year: 'numeric'
                        });
                        const currentMonth = currentDate.toLocaleString('en-US', {
                            timeZone: 'Asia/Manila',
                            month: '2-digit'
                        });

                        let startYear = 2023;
                        let endYear = parseInt(currentYear);
                        let endMonth = parseInt(currentMonth);

                        for (let year = startYear; year <= endYear; year++) {
                            let startMonth = year === startYear ? 1 : 1;
                            let endLoopMonth = year === endYear ? endMonth : 12;

                            for (let month = startMonth; month <= endLoopMonth; month++) {
                                const formattedMonth = year + '-' + String(month).padStart(2, '0');

                                const option = document.createElement("option");
                                option.value = formattedMonth;
                                option.textContent = new Date(formattedMonth + '-01').toLocaleString('en-US', {
                                    timeZone: 'Asia/Manila',
                                    month: 'long',
                                    year: 'numeric'
                                });

                                if (formattedMonth === currentYear + '-' + currentMonth) {
                                    option.selected = true;
                                }

                                monthSelect.appendChild(option);
                            }
                        }
                    });
                </script>

                <!-- daily bargraph -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const monthSelect = document.getElementById("monthSelect");
                        const barChartCanvas = document.getElementById("barChartDay").getContext('2d');
                        let barChart;

                        const viewModal = document.getElementById("viewModal");
                        const modalContent = viewModal.querySelector("div > div");

                        function closeViewModal() {
                            viewModal.classList.remove('show');
                        }

                        document.querySelector(".modal-close").addEventListener("click", closeViewModal);

                        // Function to fetch and display data for a clicked bar
                        function openViewModal(day, type, month) {
                            modalContent.innerHTML = `
                <div class="row row-between">
                    <div class="title-26px">View | Report</div>
                    <span class="modal-close" onclick="closeViewModal()">&times;</span>
                </div>
                <div>Loading data...</div>
            `;
                            viewModal.classList.add('show');

                            // Fetch details from the backend
                            fetch(`functions/fetch_borrow_details_day.php?day=${day}&type=${type}&month=${month}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.details.length > 0) {
                                        const tableRows = data.details
                                            .map(detail => `
                                <tr>
                                    <td>${detail.date}</td>
                                    <td>${detail.bookTitle}</td>
                                    <td>${detail.patron}</td>
                                </tr>
                            `)
                                            .join("");

                                        modalContent.innerHTML = `
                            <div class="row row-between">
                                <div class="title-26px">View | Report</div>
                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Book Title</th>
                                        <th>Patron</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        `;
                                    } else {
                                        modalContent.innerHTML = `
                            <div class="row row-between">
                                <div class="title-26px">View | Report</div>
                                <span class="modal-close" onclick="closeViewModal()">&times;</span>
                            </div>
                            <div>No data available for this selection.</div>
                        `;
                                    }
                                })
                                .catch(error => {
                                    console.error("Error fetching details:", error);
                                    modalContent.innerHTML = `
                        <div class="row row-between">
                            <div class="title-26px">View | Report</div>
                            <span class="modal-close" onclick="closeViewModal()">&times;</span>
                        </div>
                        <div>Error loading data. Please try again later.</div>
                    `;
                                });
                        }

                        // Function to fetch and display data based on the selected month
                        function fetchDataForMonth(month) {
                            fetch(`functions/fetch_borrow_data_day.php?month=${month}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (barChart) {
                                        barChart.destroy();
                                    }

                                    barChart = new Chart(barChartCanvas, {
                                        type: 'bar',
                                        data: {
                                            labels: data.labels, // Days of the month: ["1", "2", "3", ...]
                                            datasets: [{
                                                    label: `Borrowed in ${month}`,
                                                    data: data.borrowed, // Counts for each day
                                                    backgroundColor: '#BFFF00',
                                                    borderWidth: 1,
                                                },
                                                {
                                                    label: `Returned in ${month}`,
                                                    data: data.returned, // Counts for each day
                                                    backgroundColor: '#FFFFE0',
                                                    borderWidth: 1,
                                                },
                                            ],
                                        },
                                        options: {
                                            responsive: true,
                                            onClick: (event, elements) => {
                                                if (elements.length > 0) {
                                                    const element = elements[0];
                                                    const datasetIndex = element.datasetIndex;
                                                    const dataIndex = element.index;

                                                    const type = barChart.data.datasets[datasetIndex].label.includes("Borrowed") ? "borrowed" : "returned";
                                                    const day = barChart.data.labels[dataIndex]; // Day of the month

                                                    openViewModal(day, type, month); // Open the modal with data
                                                }
                                            },
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
                                                    },
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
                                                    },
                                                },
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
                                .catch(error => console.error("Error fetching data:", error));
                        }

                        // Fetch data for the default month on page load
                        fetchDataForMonth(monthSelect.value);

                        // Update data when a new month is selected
                        monthSelect.addEventListener("change", function() {
                            const selectedMonth = monthSelect.value;
                            fetchDataForMonth(selectedMonth);
                        });
                    });
                </script>






                <?php
                include 'modal/view_report_modal.php';
                ?>




            </div>

        </div>





    </div>
</body>

</html>





<script src="js/close-status.js"></script>