<?php
function getLokasi($conn) {
    $sql = "SELECT * FROM Lokasi";
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
getLokasi($conn);
?>
