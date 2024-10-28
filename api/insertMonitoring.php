<?php
function insertMonitoring($conn) {
    $sumberDayaId = $_POST['sumberdaya_id'];
    $tegangan = $_POST['tegangan'];
    $arus = $_POST['arus'];
    $aktivitas = $_POST['aktivitas'];
    $status = $_POST['status'];
    $suhu = $_POST['suhu'];
    $sql = "INSERT INTO Monitoring (sumberdaya_id, tegangan, arus, aktivitas, status, suhu) VALUES ('$sumberDayaId', '$tegangan', '$arus', '$aktivitas', '$status', '$suhu')";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data monitoring berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data monitoring']);
    }
}
insertMonitoring($conn);
?>
