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
            <div style="position: relative;">
                <i class="fas fa-bell notification-icon" id="notification-icon"></i>
                <span class="notification-badge" id="notification-badge">0</span>
            </div>
            <div id="notification-message" class="notification-message">
                Tidak ada notifikasi baru
            </div>
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
                // Initialize date picker and update data
                document.getElementById("datePicker").addEventListener("change", function(event) {

                    const selectedDate = event.target.value;
                    console.log("berubah tod --> ", selectedDate)
                    updateSoCData(selectedDate);
                });

                // Set default date and fetch initial data
                const today = new Date().toISOString().split("T")[0];
                document.getElementById("datePicker").value = today;
                updateSoCData(today);

                // Fetch and update table dynamically
                // function updateTable(date) {
                //     fetch(`api/soc.php?date=${date}`)
                //         .then(response => response.json())
                //         .then(data => {
                //             const tableBody = document.getElementById("tableBody");
                //             tableBody.innerHTML = ""; // Clear existing rows

                //             data.forEach(row => {

                //                 console.log("row.voltage --> ", row.voltage)
                //                 const tableRow = `
                //                     <tr>
                //                         <td><i class="fas fa-car-battery"></i> ${row.name}</td>
                //                         <td>${row.voltage}</td>
                //                         <td>${row.current}</td>
                //                         <td>${row.soc}</td>
                //                         <td>${row.estimatedTime}</td>
                //                         <td>${row.status}</td>
                //                     </tr>`;
                //                 tableBody.insertAdjacentHTML("beforeend", tableRow);
                //             });
                //         })
                //         .catch(error => console.error('Error fetching table data:', error));
                // }
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