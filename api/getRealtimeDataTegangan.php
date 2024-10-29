<?php

function getRealtimeDataTegangan()
{
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "monitoring_power";

    $sumberdaya_id = $_GET['sumberdaya_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch data from RealtimeData table
    $sql = "SELECT waktu_pengukuran, tegangan FROM RealtimeData WHERE sumberdaya_id = $sumberdaya_id ORDER by waktu_pengukuran ASC";
    $result = $conn->query($sql);

    $averageData = [];

    // Process each row
    while ($row = $result->fetch_assoc()) {
        // Format waktu_pengukuran to HH:mm:ss
        $time = date("H:i:s", strtotime($row['waktu_pengukuran']));

        // Populate array with formatted time as key and tegangan as value
        $averageData[$time] = $row['tegangan'];
    }

    // Output as JSON
    echo json_encode($averageData);

    // Close connection
    $conn->close();
}

// Call the function
getRealtimeDataTegangan();
?>
