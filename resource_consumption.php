<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './const/const.php';
    include("./component/header.php");
    ?>
    <title>Konsumsi Daya</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="sidebar" id="sidebar">
        <?php include("./component/sidebar.php"); ?>
    </div>

    <div class="content" id="content">
        <div class="header">
            <i class="fas fa-bars menu-icon" id="menu-icon"></i>
            <h1>Konsumsi Daya</h1>
            <?php include("./component/notification.php"); ?>
        </div>
        <div class="main-content" style="margin-top: 20px">
            <div class="route-utama">Accu</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Sumber Daya</th>
                            <th>Tegangan (V)</th>
                            <th>Arus (A)</th>
                            <th>SoC (%)</th>
                            <th>Estimasi Waktu</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td><i class="fas fa-car-battery"></i> Reading...</td>
                            <td>Reading...</td>
                            <td>Reading...</td>
                            <td>Reading...</td>
                            <td>Reading...</td>
                            <td>Reading...</td>
                        </tr>
                        <!-- Dynamic table rows will be injected here -->
                    </tbody>
                </table>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="date">
                        <input type="date" id="datePicker" class="date-picker" />
                    </div>
                </div>
                <div class="canvas-container">
                    <canvas id="socChart" width="800" height="400"></canvas>
                </div>
            </div>
            <script src="js/socGraph.js"></script>
            <script>
                let intervalId; // To store the interval ID
                const today = new Date().toISOString().split("T")[0];
                const datePicker = document.getElementById("datePicker");

                // Initialize the date picker with today's date
                datePicker.value = today;

                // Function to clear the interval
                function clearUpdateInterval() {
                    if (intervalId) {
                        clearInterval(intervalId);
                        intervalId = null;
                    }
                }

                // Function to handle date change
                function handleDateChange(selectedDate) {
                    if (selectedDate === today) {
                        // If the selected date is today, run updateSoCData in an interval
                        clearUpdateInterval(); // Clear any existing interval
                        intervalId = setInterval(() => {
                            updateSoCData(selectedDate);
                        }, 2000); // Update every 2 seconds
                    } else {
                        // If the selected date is not today, run updateSoCData only once
                        clearUpdateInterval(); // Clear any existing interval
                        updateSoCData(selectedDate);
                    }
                }

                // Add event listener for date picker changes
                datePicker.addEventListener("change", function(event) {
                    const selectedDate = event.target.value;
                    handleDateChange(selectedDate);
                });

                // Set initial state to today's date
                handleDateChange(today);
            </script>

        </div>
    </div>

    <script>
        const menuIcon = document.getElementById("menu-icon");
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");

        menuIcon.addEventListener("click", () => {
            sidebar.classList.toggle("active");
            content.classList.toggle("active");
        });
    </script>
</body>

</html>