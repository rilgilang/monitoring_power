<?php
function insertLokasi($conn) {
    $namaLokasi = $_POST['nama_lokasi'];
    $sql = "INSERT INTO Lokasi (nama_lokasi) VALUES ('$namaLokasi')";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data lokasi berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data lokasi']);
    }
}
insertLokasi($conn);
?>