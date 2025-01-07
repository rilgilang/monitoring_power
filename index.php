<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './const/const.php';
    include("./component/header.php");
    ?>
    <title>Dashboard</title>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <?php include("./component/sidebar.php"); ?>
    </div>

    <div class="content" id="content">
        <div class="header">
            <i class="fas fa-bars menu-icon" id="menu-icon"></i>
            <h1>DASHBOARD</h1>
            <?php include("./component/notification.php"); ?>
            <div id="notification-message" class="notification-message">Tidak ada notifikasi baru</div>
        </div>

        <div>
            <div class="main-content">
                <div class="grid-container">
                    <div class="route-utama">Route Utama</div>
                    <div class="temperature">
                        Suhu <span id="device-1-temperature" class="temperature-value">0°C</span>
                    </div>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sumber Daya</th>
                                    <th>Tegangan</th>
                                    <th>Arus</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody1">
                                <tr>
                                    <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                                    <td id="device-1-pln-voltage">Reading...</td>
                                    <td id="device-1-pln-current">Reading...</td>
                                    <td id="device-1-pln-activity">Reading...</td>
                                    <td><span id="device-1-pln-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-car-battery"></i> Accu</td>
                                    <td id="device-1-accu-voltage">Reading...</td>
                                    <td id="device-1-accu-current">Reading...</td>
                                    <td id="device-1-accu-activity">Reading...</td>
                                    <td><span id="device-1-accu-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
                                    <td id="device-1-ups-voltage">Reading...</td>
                                    <td id="device-1-ups-current">Reading...</td>
                                    <td id="device-1-ups-activity">Reading...</td>
                                    <td><span id="device-1-ups-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="grid-container">
                    <div class="route-utama">BTS 1</div>
                    <div class="temperature">
                        Suhu <span id="device-2-temperature" class="temperature-value">0°C</span>
                    </div>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sumber Daya</th>
                                    <th>Tegangan</th>
                                    <th>Arus</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody2">
                                <tr>
                                    <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                                    <td id="device-2-pln-voltage">Reading...</td>
                                    <td id="device-2-pln-current">Reading...</td>
                                    <td id="device-2-pln-activity">Reading...</td>
                                    <td><span id="device-2-pln-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-car-battery"></i> Accu</td>
                                    <td id="device-2-accu-voltage">Reading...</td>
                                    <td id="device-2-accu-current">Reading...</td>
                                    <td id="device-2-accu-activity">Reading...</td>
                                    <td><span id="device-2-accu-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
                                    <td id="device-2-ups-voltage">Reading...</td>
                                    <td id="device-2-ups-current">Reading...</td>
                                    <td id="device-2-ups-activity">Reading...</td>
                                    <td><span id="device-2-ups-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="grid-container">
                    <div class="route-utama">BTS 2</div>
                    <div class="temperature">
                        Suhu <span id="device-3-temperature" class="temperature-value">0°C</span>
                    </div>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sumber Daya</th>
                                    <th>Tegangan</th>
                                    <th>Arus</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody3">
                                <tr>
                                    <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                                    <td id="device-3-pln-voltage">Reading...</td>
                                    <td id="device-3-pln-current">Reading...</td>
                                    <td id="device-3-pln-activity">Reading...</td>
                                    <td><span id="device-3-pln-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-car-battery"></i> Accu</td>
                                    <td id="device-3-accu-voltage">Reading...</td>
                                    <td id="device-3-accu-current">Reading...</td>
                                    <td id="device-3-accu-activity">Reading...</td>
                                    <td><span id="device-3-accu-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
                                    <td id="device-3-ups-voltage">Reading...</td>
                                    <td id="device-3-ups-current">Reading...</td>
                                    <td id="device-3-ups-activity">Reading...</td>
                                    <td><span id="device-3-ups-status" class="status tidak-aktif">Reading...</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- <script type="module" src="js/websocket.js"></script> -->
        <script src="js/monitoring.js"></script>
        <script src="js/script.js"></script>
    </div>
</body>

</html>