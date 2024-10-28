<?php
function insertNotifikasi($conn) {
    $monitoringId = $_POST['monitoring_id'];
    $pesan = $_POST['pesan'];
    $sql = "INSERT INTO Notifikasi (monitoring_id, pesan) VALUES ('$monitoringId', '$pesan')";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data notifikasi berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data notifikasi']);
    }
}
insertNotifikasi($conn);
?>