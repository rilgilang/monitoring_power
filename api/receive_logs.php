<?php
header('Content-Type: application/json');

// Include database connection
include './config.php';

try {
    // Get the content type of the request
    $contentType = $_SERVER['CONTENT_TYPE'];
    $reqBody = strtolower($contentType);

    // Initialize data array
    $data = [];

    if (strpos($reqBody, 'form-data') !== false) {
        // Get data from form-data
        $data = [
            'temperature' => $_POST['temperature'],
            'pln_volt' => $_POST['pln_volt'],
            'pln_current' => $_POST['pln_current'],
            'pln_activity' => $_POST['pln_activity'],
            'pln_status' => $_POST['pln_status'],
            'accu_volt' => $_POST['accu_volt'],
            'accu_current' => $_POST['accu_current'],
            'accu_activity' => $_POST['accu_activity'],
            'accu_status' => $_POST['accu_status'],
            'ups_volt' => $_POST['ups_volt'],
            'ups_current' => $_POST['ups_current'],
            'ups_activity' => $_POST['ups_activity'],
            'ups_status' => $_POST['ups_status'],
            'device_id' => $_POST['device_id'],
            'soc' => $_POST['soc'],
            'accu_estimate_time' => $_POST['accu_estimate_time'],
            'accu_info' => $_POST['accu_info']
        ];
    } elseif (strpos($reqBody, 'json') !== false) {
        // Get data from JSON body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    } else {
        // Unsupported content type
        http_response_code(400);
        echo json_encode(['message' => 'Unsupported Content-Type']);
        exit;
    }

    // Validate required fields
    $requiredFields = [
        'temperature', 'pln_volt', 'pln_current', 'pln_activity', 'pln_status',
        'accu_volt', 'accu_current', 'accu_activity', 'accu_status',
        'ups_volt', 'ups_current', 'ups_activity', 'ups_status',
        'device_id', 'soc', 'accu_estimate_time', 'accu_info'
    ];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['message' => "Missing required field: $field"]);
            exit;
        }
    }

    // Prepare SQL query
    $stmt = $pdo->prepare("
        INSERT INTO log (
            temperature, pln_volt, pln_current, pln_activity, pln_status,
            accu_volt, accu_current, accu_activity, accu_status,
            ups_volt, ups_current, ups_activity, ups_status,
            device_id, soc, accu_estimate_time, accu_info
        ) VALUES (
            :temperature, :pln_volt, :pln_current, :pln_activity, :pln_status,
            :accu_volt, :accu_current, :accu_activity, :accu_status,
            :ups_volt, :ups_current, :ups_activity, :ups_status,
            :device_id, :soc, :accu_estimate_time, :accu_info
        )
    ");

    // Bind parameters and execute query
    $stmt->execute([
        ':temperature' => $data['temperature'],
        ':pln_volt' => $data['pln_volt'],
        ':pln_current' => $data['pln_current'],
        ':pln_activity' => $data['pln_activity'],
        ':pln_status' => $data['pln_status'],
        ':accu_volt' => $data['accu_volt'],
        ':accu_current' => $data['accu_current'],
        ':accu_activity' => $data['accu_activity'],
        ':accu_status' => $data['accu_status'],
        ':ups_volt' => $data['ups_volt'],
        ':ups_current' => $data['ups_current'],
        ':ups_activity' => $data['ups_activity'],
        ':ups_status' => $data['ups_status'],
        ':device_id' => $data['device_id'],
        ':soc' => $data['soc'],
        ':accu_estimate_time' => $data['accu_estimate_time'],
        ':accu_info' => $data['accu_info']
    ]);

    // Return OK response
    http_response_code(200);
    echo json_encode(['message' => 'OK']);
} catch (Exception $e) {
    // Handle exceptions
    http_response_code(500);
    echo json_encode(['message' => 'Error occurred', 'error' => $e->getMessage()]);
}
