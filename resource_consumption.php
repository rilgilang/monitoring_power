<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './const/const.php';
    include("./component/header.php");
    ?>
    <title>Konsumsi Daya</title>
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
                    <tbody>
                        <tr>
                            <td><i class="fas fa-car-battery"></i> Accu Route Utama</td>
                            <td>12.5</td>
                            <td>5</td>
                            <td>80</td>
                            <td>2 Jam 30 Menit</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-car-battery"></i> Accu BTS 1</td>
                            <td>12.2</td>
                            <td>4.5</td>
                            <td>75</td>
                            <td>2 Jam 30 Menit</td>
                            <td>Penuh</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-car-battery"></i> Accu BTS 2</td>
                            <td>12.0</td>
                            <td>4</td>
                            <td>70</td>
                            <td>30 Menit</td>
                            <td>Penuh</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="date">
                        <input type="date" id="datePicker" class="date-picker" />
                    </div>
                </div>
                <div class="canvas-container" style="height: 400px;">
                    <canvas id="socChart"></canvas>
                </div>
            </div>
            <script src="js/socGraph.js"></script>
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
                    .addEventListener("change", function(event) {
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
                    // Ambil notifikasi acak dari array
                    const randomIndex = Math.floor(Math.random() * notifications.length);
                    const newNotification = notifications[randomIndex];

                    // Pastikan notifikasi belum ditampilkan sebelumnya
                    if (!displayedNotifications.includes(newNotification)) {
                        displayedNotifications.push(newNotification); // Tambahkan ke daftar yang ditampilkan

                        // Tambahkan notifikasi ke dalam elemen message
                        const notificationDiv = document.createElement("div");
                        notificationDiv.textContent = newNotification;
                        notificationDiv.className = "notification-item";
                        notificationMessage.appendChild(notificationDiv);

                        // Update jumlah notifikasi di badge
                        notificationBadge.textContent = displayedNotifications.length;
                    }
                }

                // Menambahkan notifikasi baru setiap 5 detik
                setInterval(showNotification, 5000);

                menuIcon.addEventListener("click", () => {
                    sidebar.classList.toggle("active");
                    sidebar.classList.toggle("small");
                    content.classList.toggle("active");
                });

                notificationIcon.addEventListener("click", () => {
                    // Tampilkan atau sembunyikan pesan notifikasi
                    if (
                        notificationMessage.style.display === "none" ||
                        notificationMessage.style.display === ""
                    ) {
                        notificationMessage.style.display = "block"; // Tampilkan pesan
                    } else {
                        notificationMessage.style.display = "none"; // Sembunyikan pesan
                    }
                });

                // Menyembunyikan notifikasi jika mengklik di luar elemen
                document.addEventListener("click", (event) => {
                    if (
                        !notificationIcon.contains(event.target) &&
                        !notificationMessage.contains(event.target)
                    ) {
                        notificationMessage.style.display = "none"; // Sembunyikan jika klik di luar
                    }
                });
            </script>
</body>

</html>