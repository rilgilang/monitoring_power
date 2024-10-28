<?php
function getLogAktivitas($conn) {
    $sql = "SELECT * FROM LogAktivitas";
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
getLogAktivitas($conn);
?>
