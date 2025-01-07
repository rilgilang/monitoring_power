<?php

include './config.php';


header('Content-Type: application/json');

// Input validation
if (!isset($_GET['date']) || empty($_GET['date'])) {
    echo json_encode(['error' => 'date parameter is required.']);
    exit;
}

if (!isset($_GET['device_id']) || empty($_GET['device_id'])) {
    echo json_encode(['error' => 'device_id parameter is required.']);
    exit;
}

$id = (int)$_GET['device_id'];
$date = $_GET['date'];

// Get the PDO instance
global $pdo;

$query = "SELECT pln_volt, pln_current, accu_volt, accu_current, ups_volt, ups_current , created_at FROM log WHERE device_id = ? AND DATE(created_at) = ?";

$queryParams = [$id, $date];

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($queryParams);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

    // Initialize arrays for chart data
    $labels = [];
    $pln_voltages = [];
    $pln_currents = [];
    $accu_voltages = [];
    $accu_currents = [];
    $ups_voltages = [];
    $ups_currents = [];

    error_log(json_encode($logs, JSON_UNESCAPED_UNICODE));

    // Restructure the data
    foreach ($logs as $log) {
        error_log($log['pln_volt']);
        $labels[] = date('H:i', strtotime($log['created_at'])); // Format time as "HH:mm"
        $pln_voltages[] = (float) $log['pln_volt']; // Convert voltage to float
        $pln_currents[] = (float) $log['pln_current']; // Convert current to float
        $accu_voltages[] = (float) $log['accu_volt']; // Convert voltage to float
        $accu_currents[] = (float) $log['accu_volt']; // Convert voltage to float
        $ups_voltages[] = (float) $log['ups_volt']; // Convert voltage to float
        $ups_currents[] = (float) $log['ups_current']; // Convert voltage to float
    }


    // Table data
    // Fetch latest data
    $extraQuery = "
         SELECT temperature, pln_volt AS pln_voltage, pln_current, pln_activity, pln_status, 
                accu_volt AS accu_voltage, accu_current, accu_activity, accu_status, 
                ups_volt AS ups_voltage, ups_current, ups_activity, ups_status
         FROM log 
         WHERE device_id = ? AND DATE(created_at) = CURDATE() ORDER BY created_at DESC LIMIT 1";
    $extraStmt = $pdo->prepare($extraQuery);
    $extraStmt->execute([$id]);
    $extraData = $extraStmt->fetch(PDO::FETCH_ASSOC);

    $pln = [];

    $accu = [];

    $ups = [];

    if ($extraData != []) {
        $pln = [
            'voltage' => (float) $extraData['pln_voltage'],
            'current' => (float) $extraData['pln_current'],
            'activity' => $extraData['pln_activity'],
            'status' => $extraData['pln_status'],
        ];

        $accu = [
            'voltage' => (float) $extraData['accu_voltage'],
            'current' => (float) $extraData['accu_current'],
            'activity' => $extraData['accu_activity'],
            'status' => $extraData['accu_status'],
        ];

        $ups = [
            'voltage' => (float) $extraData['ups_voltage'],
            'current' => (float) $extraData['ups_current'],
            'activity' => $extraData['ups_activity'],
            'status' => $extraData['ups_status'],
        ];
    }



    // Build the desired JSON structure
    $response = [
        'labels' => $labels,
        'data' => [
            'graph' => [
                [
                    'label_key' => 'pln_volt',
                    'data' => $pln_voltages,
                ],
                [
                    'label_key' => 'pln_current',
                    'data' => $pln_currents,
                ],
                [
                    'label_key' => 'accu_volt',
                    'data' => $accu_voltages,
                ],
                [
                    'label_key' => 'accu_current',
                    'data' => $accu_currents,
                ],
                [
                    'label_key' => 'ups_volt',
                    'data' => $ups_voltages,
                ],
                [
                    'label_key' => 'ups_current',
                    'data' => $ups_currents,
                ],
            ],
            // 'status_changed' => true,
            'temperature' => $extraData != [] ? (float) $extraData['temperature'] : 0,
            'pln' => $pln,
            'accu' => $accu,
            'ups' => $ups,
        ],
    ];

    // Return the JSON response
    echo json_encode($response);
} catch (PDOException $e) {
    // Return error message
    echo json_encode(['error' => $e->getMessage(), 'status' => 'failed']);
}
