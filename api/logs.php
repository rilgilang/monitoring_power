<?php

include './config.php';


header('Content-Type: application/json');

// Get the PDO instance
global $pdo;

try {

    // Query to fetch data
    $stmt = $pdo->query("SELECT * FROM log");
    $data = [];



    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [
            'timestamp' => $row['created_at'],
            'pln' => [
                'volt' => $row['pln_volt'],
                'current' => $row['pln_current'],
                'activity' => $row['pln_activity'],
                'status' => $row['pln_status']
            ],
            'accu' => [
                'volt' => $row['accu_volt'],
                'current' => $row['accu_current'],
                'activity' => $row['accu_activity'],
                'status' => $row['accu_status']
            ],
            'ups' => [
                'volt' => $row['ups_volt'],
                'current' => $row['ups_current'],
                'activity' => $row['ups_activity'],
                'status' => $row['ups_status']
            ]
        ];
    }

    echo json_encode($data);
} catch (PDOException $e) {
    // Return error message
    echo json_encode(['error' => $e->getMessage(), 'status' => 'failed']);
}
