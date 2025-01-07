<?php

include './config.php';

// Input validation
if (!isset($_GET['date']) || empty($_GET['date'])) {
    echo json_encode(['error' => 'date parameter is required.']);
    exit;
}

$date = $_GET['date'];

// Query to fetch data
try {
    // Fetch all rows for graph data
    $stmtGraph = $pdo->prepare("
        SELECT device_id, pln_volt, pln_current, accu_volt, accu_current, 
               ups_volt, ups_current, soc, accu_estimate_time, created_at 
        FROM log 
        WHERE DATE(created_at) = :date 
        ORDER BY created_at ASC
    ");
    $stmtGraph->bindParam(':date', $date);
    $stmtGraph->execute();
    $graphData = $stmtGraph->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the latest row for each device_id for the table
    $stmtTable = $pdo->prepare("
        SELECT t.device_id, device_name, t.pln_volt, t.pln_current, t.accu_volt, t.accu_current, t.accu_info,
               t.ups_volt, t.ups_current, t.soc, t.soe, t.accu_estimate_time, t.created_at 
        FROM log t
        JOIN device d on t.device_id = d.id
        GROUP BY t.device_id
        ORDER BY t.device_id ASC
    ");
    $stmtTable->bindParam(':date', $date);
    $stmtTable->execute();
    $tableData = $stmtTable->fetchAll(PDO::FETCH_ASSOC);

    // Prepare JSON response
    $response = [
        "labels" => [],
        "data" => [
            "graph" => [],
            "table" => $tableData
        ]
    ];

    foreach ($graphData as $row) {
        $response['labels'][] = $row['created_at'];
        $response['data']['graph'][] = [
            'soc' => $row['soc'],
            'device_id' => $row['device_id'],
        ];
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    exit;
}
