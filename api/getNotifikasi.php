<?php
function getNotifikasi($conn) {
    $sql = "SELECT * FROM Notifikasi";
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
getNotifikasi($conn);
?>
