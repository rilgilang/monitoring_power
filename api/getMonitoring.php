<?php
function getMonitoring($conn) {
    $sql = "SELECT * FROM Monitoring";
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
getMonitoring($conn);
?>
