<?php

function getRealtimeDataArus()
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
    $sql = "SELECT waktu_pengukuran, arus FROM RealtimeData WHERE sumberdaya_id = $sumberdaya_id";
    $result = $conn->query($sql);

    $hourlyData = [];
    $hourlyCount = [];

    // Process each row
    while ($row = $result->fetch_assoc()) {
        // Extract hour from the datetime field
        $hour = date("H:00", strtotime($row['waktu_pengukuran']));

        // Initialize or accumulate arus and count for each hour
        if (isset($hourlyData[$hour])) {
            $hourlyData[$hour] += $row['arus'];
            $hourlyCount[$hour]++;
        } else {
            $hourlyData[$hour] = $row['arus'];
            $hourlyCount[$hour] = 1;
        }
    }

    // Calculate average for each hour
    $averageData = [];
    foreach ($hourlyData as $hour => $sum) {
        $averageData[$hour] = $sum / $hourlyCount[$hour];
    }

    // Output as JSON
    echo json_encode($averageData);

    // Close connection
    $conn->close();
}

// Call the function
getRealtimeDataArus();
?>
