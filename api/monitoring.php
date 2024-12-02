<?php 

include './config.php';

function monitoring($id, $date) {
    // Get the PDO instance
    global $pdo;

    $query = "SELECT * FROM log WHERE device_id = ? AND DATE(created_at) = ?";

    $queryParams = [$id, "CURDATE()"];

    

    if ($date != ""){
        $queryParams[1] = $date;
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($queryParams);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

        // Initialize arrays for chart data
        $labels = [];
        $pln_voltages = [];
        $pln_currents = [];

        // Restructure the data
        foreach ($logs as $log) {
            $labels[] = date('H:i', strtotime($log['created_at'])); // Format time as "HH:mm"
            $pln_voltages[] = (float) $log['pln_volt']; // Convert voltage to float
            $pln_currents[] = (float) $log['pln_current']; // Convert current to float
        }

        // Build the desired JSON structure
        $response = [
            'labels' => $labels,
            'data' => [
                [
                    'label_key' => 'pln_volt',
                    'data' => $pln_voltages,
                ],
                [
                    'label_key' => 'pln_current',
                    'data' => $pln_currents,
                ],
            ],
        ];

        // Return the JSON response
        echo json_encode($response);

    } catch (PDOException $e) {
        // Return error message
        echo json_encode(['error' => $e->getMessage(), 'status' => 'failed']);
    }
}

// Get the device ID from the query string
if (isset($_GET['device_id']) && is_numeric($_GET['device_id'])) {
    $device_id = (int)$_GET['device_id'];
    $date = "";

    
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
    }

    monitoring($device_id, $date);
} else {
    echo json_encode(['error' => 'Invalid ID parameter', 'status' => 'failed']);
}
?>
