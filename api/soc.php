<?php

include './config.php';

function soc($date = "")
{
    // Get the PDO instance
    global $pdo;

    $query = "SELECT device_id, pln_volt, pln_current, accu_volt, accu_current, ups_volt, ups_current, soc, accu_estimate_time, created_at 
              FROM log 
              WHERE DATE(created_at) = :date
              ORDER BY created_at ASC";

    // Use today's date if no date is provided
    $queryParams = [
        ':date' => $date ? $date : date('Y-m-d')
    ];

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($queryParams);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize arrays for grouped data
        $labels = [];
        $graphData = [];
        $socData = [];

        foreach ($logs as $log) {
            $deviceId = $log['device_id'];

            // Add label if not already added
            $timeLabel = date('H:i', strtotime($log['created_at']));
            if (!in_array($timeLabel, $labels)) {
                $labels[] = $timeLabel;
            }

            // Initialize device-specific arrays if not set
            if (!isset($graphData[$deviceId])) {
                $graphData[$deviceId] = [
                    'soc' => [],
                ];
            }

            // Push data into the respective device's graph arrays
            $graphData[$deviceId]['soc'][] = (float)$log['soc'];

            // Prepare SOC data for the device
            if (!isset($socData[$deviceId])) {
                $socData[$deviceId] = [
                    'soc' => (float)$log['soc'],
                    'accu_estimate_time' => (int)$log['accu_estimate_time'],
                    'accu_volt' => (float)$log['accu_volt'],
                    'accu_current' => (float)$log['accu_current'],
                ];
            }
        }

        // Build the JSON structure
        $response = [
            'labels' => $labels,
            'data' => [
                'graph' => $graphData,
                'soc' => $socData,
            ],
        ];

        // Return the JSON response
        echo json_encode($response);
    } catch (PDOException $e) {
        // Return error message
        echo json_encode(['error' => $e->getMessage(), 'status' => 'failed']);
    }
}

// Get the date parameter (optional)
$date = isset($_GET['date']) ? $_GET['date'] : "";
soc($date);
