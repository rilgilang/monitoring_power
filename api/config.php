<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Monitoring_power";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle request berdasarkan action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    switch ($action) {
        case 'insertLokasi':
            include 'insertLokasi.php';
            break;
        case 'insertSumberDaya':
            include 'insertSumberDaya.php';
            break;
        case 'insertMonitoring':
            include 'insertMonitoring.php';
            break;
        case 'insertRealtimeData':
            include 'insertRealtimeData.php';
            break;
        case 'insertLogAktivitas':
            include 'insertLogAktivitas.php';
            break;
        case 'insertNotifikasi':
            include 'insertNotifikasi.php';
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'];
    switch ($action) {
        case 'getLokasi':
            include 'getLokasi.php';
            break;
        case 'getSumberDaya':
            include 'getSumberDaya.php';
            break;
        case 'getMonitoring':
            include 'getMonitoring.php';
            break;
        case 'getRealtimeDataTegangan':
            include 'getRealtimeDataTegangan.php';
            break;
        case 'getLogAktivitas':
            include 'getLogAktivitas.php';
            break;
        case 'getNotifikasi':
            include 'getNotifikasi.php';
            break;
    }
}

$conn->close();
?>