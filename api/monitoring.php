<?php

include './config.php';


header('Content-Type: application/json');

// Get the PDO instance
global $pdo;

try {


    $response = [
        "device" => []
    ];

    // Table data
    // Fetch latest data
    $q = "";
    $stmt = $pdo->prepare($q);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log(json_encode($data));
    // Return the JSON response
    echo json_encode($data);
} catch (PDOException $e) {
    // Return error message
    echo json_encode(['error' => $e->getMessage(), 'status' => 'failed']);
}
