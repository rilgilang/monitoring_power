<?php
function insertSumberDaya($conn) {
    $lokasiId = $_POST['lokasi_id'];
    $jenisSumberDaya = $_POST['jenis_sumberdaya'];
    $sql = "INSERT INTO SumberDaya (lokasi_id, jenis_sumberdaya) VALUES ('$lokasiId', '$jenisSumberDaya')";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data sumber daya berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data sumber daya']);
    }
}
insertSumberDaya($conn);
?>
