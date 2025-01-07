<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './const/const.php';
    include("./component/header.php");
    ?>
    <title>Log Aktivitas</title>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <?php include("./component/sidebar.php"); ?>
    </div>

    <div class="content" id="content">
        <div class="header">
            <i class="fas fa-bars menu-icon" id="menu-icon"></i>
            <h1>LOG AKTIVITAS</h1>
            <?php include("./component/notification.php"); ?>
        </div>

        <div class="main-content">
            <div class="route-utama" style="margin-top: 20px">Log Aktivitas</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Sumber Daya</th>
                            <th>Tegangan</th>
                            <th>Arus</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data rows will be populated here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Fetch data from the backend and populate the table
        async function fetchDataAndPopulateTable() {
            try {
                const response = await fetch('/api/logs.php');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = ''; // Clear existing rows

                data.forEach(entry => {
                    // Add PLN row
                    const plnRow = createTableRow(entry.timestamp, 'PLN', entry.pln.volt, entry.pln.current, entry.pln.activity, entry.pln.status);
                    tableBody.appendChild(plnRow);

                    // Add Accu row
                    const accuRow = createTableRow(entry.timestamp, 'Accu', entry.accu.volt, entry.accu.current, entry.accu.activity, entry.accu.status);
                    tableBody.appendChild(accuRow);

                    // Add UPS row
                    const upsRow = createTableRow(entry.timestamp, 'UPS', entry.ups.volt, entry.ups.current, entry.ups.activity, entry.ups.status);
                    tableBody.appendChild(upsRow);
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Helper function to create a table row
        function createTableRow(timestamp, source, voltage, current, activity, status) {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${timestamp}</td>
                <td>${source}</td>
                <td>${voltage}</td>
                <td>${current}</td>
                <td>${activity}</td>
                <td>${status}</td>
            `;

            return row;
        }

        // Call fetchDataAndPopulateTable on page load
        setInterval(() => {
            fetchDataAndPopulateTable();
        }, 2000);

        fetchDataAndPopulateTable();
    </script>

    <script src="js/script.js"></script>
</body>

</html>