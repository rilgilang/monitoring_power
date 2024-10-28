<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BTS 2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/route.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <i class="fas fa-bolt" style="font-size: 50px; margin-bottom: 10px"></i>
        <h2>Monitoring Power</h2>
        <a href="index.php"><i class="fas fa-tachometer-alt"></i> DASHBOARD</a>
        <a href="route_utama.php"><i class="fas fa-circle"></i> Route Utama</a>
        <a href="bts1.php"><i class="fas fa-circle"></i> Bts 1</a>
        <a href="bts2.php"><i class="fas fa-circle"></i> Bts 2</a>
        <a href="log_aktivitas.php"><i class="fas fa-history"></i> Log Aktivitas</a>
    </div>

    <div class="content" id="content">
        <div class="header">
            <i class="fas fa-bars menu-icon" id="menu-icon"></i>
            <h1>BTS 2</h1>
            <div style="position: relative;">
                <i class="fas fa-bell notification-icon" id="notification-icon"></i>
                <span class="notification-badge" id="notification-badge">0</span>
            </div>
            <div id="notification-message" class="notification-message">
                Tidak ada notifikasi baru
            </div>
        </div>

        <div class="route-info" style="margin-top: 20px">
            <div class="route-utama">Monitoring Bts 2</div>
            <div class="temperature">
                Suhu <span class="temperature-value">36°C</span>
            </div>
        </div>

        <div class="main-content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Sumber daya</th>
                            <th>Tegangan</th>
                            <th>Arus</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                            <td>220 V</td>
                            <td>5,0 A</td>
                            <td>Tegangan Normal</td>
                            <td><span class="status aktif">Aktif</span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-car-battery"></i> Accu</td>
                            <td>12 V</td>
                            <td>0 A</td>
                            <td>Perubahan Tegangan</td>
                            <td><span class="status tidak-aktif">Tidak Aktif</span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
                            <td>12 V</td>
                            <td>0,5 A</td>
                            <td>Perubahan Tegangan</td>
                            <td><span class="status aktif">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="date" id="currentDate">
                        <input type="date" id="datePicker" class="date-picker" />
                    </div>
                    <div class="dropdown-container">
                        <select id="chartSelector" class="chart-selector">
                            <option value="listrik">Grafik Listrik</option>
                            <option value="accu">Grafik Accu</option>
                            <option value="ups">Grafik UPS</option>
                        </select>
                    </div>
                </div>
                <div class="canvas-container">
                    <canvas id="powerChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="js/chart.js"></script>
    <script>
        function updateDate() {
            const now = new Date();
            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                weekday: "long",
            };
            const dateStr = now.toLocaleDateString("id-ID", options);
            document
                .getElementById("currentDate")
                .querySelector("span").textContent = dateStr;
            document.getElementById("datePicker").value = now
                .toISOString()
                .split("T")[0];
        }

        document
            .getElementById("datePicker")
            .addEventListener("change", function (event) {
                const selectedDate = new Date(event.target.value);
                alert("Tanggal dipilih: " + selectedDate.toLocaleDateString("id-ID"));
            });

        setInterval(updateDate, 1000);
        updateDate();
    </script>
    <script>
        const menuIcon = document.getElementById("menu-icon");
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const notificationIcon = document.getElementById("notification-icon");
        const notificationMessage = document.getElementById("notification-message");
        const notificationBadge = document.getElementById("notification-badge");

        // Contoh data notifikasi
        const notifications = [
            "Notifikasi 1: Sistem berjalan dengan baik.",
            "Notifikasi 2: Pembaruan perangkat lunak tersedia.",
            "Notifikasi 3: Perubahan status pada sumber daya.",
        ];

        // Array untuk menyimpan notifikasi yang sudah ditampilkan
        let displayedNotifications = [];

        // Fungsi untuk menampilkan notifikasi baru
        function showNotification() {
            const randomIndex = Math.floor(Math.random() * notifications.length);
            const newNotification = notifications[randomIndex];

            if (!displayedNotifications.includes(newNotification)) {
                displayedNotifications.push(newNotification);

                const notificationDiv = document.createElement("div");
                notificationDiv.textContent = newNotification;
                notificationDiv.className = "notification-item";
                notificationMessage.appendChild(notificationDiv);

                notificationBadge.textContent = displayedNotifications.length;
            }
        }

        setInterval(showNotification, 5000);

        menuIcon.addEventListener("click", () => {
            sidebar.classList.toggle("small");
            content.classList.toggle("active");
        });

        notificationIcon.addEventListener("click", () => {
            if (
                notificationMessage.style.display === "none" ||
                notificationMessage.style.display === ""
            ) {
                notificationMessage.style.display = "block";
            } else {
                notificationMessage.style.display = "none";
            }
        });

        document.addEventListener("click", (event) => {
            if (
                !notificationIcon.contains(event.target) &&
                !notificationMessage.contains(event.target)
            ) {
                notificationMessage.style.display = "none";
            }
        });
    </script>
</body>
</html>
