<?php
function getSumberDaya($conn) {
    $sql = "SELECT * FROM SumberDaya";
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
getSumberDaya($conn);
?>
