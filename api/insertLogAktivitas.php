<?php
function insertLogAktivitas($conn) {
    $monitoringId = $_POST['monitoring_id'];
    $aktivitas = $_POST['aktivitas'];
    $sql = "INSERT INTO LogAktivitas (monitoring_id, aktivitas) VALUES ('$monitoringId', '$aktivitas')";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data log aktivitas berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data log aktivitas']);
    }
}
insertLogAktivitas($conn);
?>