<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
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
            <h1>DASHBOARD</h1>
            <div style="position: relative;">
                <i class="fas fa-bell notification-icon" id="notification-icon"></i>
                <span class="notification-badge" id="notification-badge">0</span>
            </div>
            <div id="notification-message" class="notification-message">
                Tidak ada notifikasi baru
            </div>
        </div>

        <div class="main-content">
            <div class="route-utama" style="margin-top: 20px">ROUTE UTAMA</div>
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
                            <td>5,5 A</td>
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
                            <td>0,6 A</td>
                            <td>Perubahan Tegangan</td>
                            <td><span class="status aktif">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="main-content" style="margin-top: 20px">
            <div class="route-utama">BTS 1</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Sumber daya</th>
                            <th>Tegangan</th >
                            <th>Arus</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                            <td>220 V</td>
                            <td>6,1 A</td>
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
                            <td>0,8 A</td>
                            <td>Perubahan Tegangan</td>
                            <td><span class="status aktif">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="main-content" style="margin-top: 20px">
            <div class="route-utama">BTS 2</div>
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
                            <td>5,5 A</td>
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
                            <td>0,6 A</td>
                            <td>Perubahan Tegangan</td>
                            <td><span class="status aktif">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
                // Ambil notifikasi acak dari array
                const randomIndex = Math.floor(Math.random() * notifications.length );
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