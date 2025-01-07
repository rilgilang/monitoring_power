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

    if (strpos($reqBody, 'form-data') !== false || strpos($reqBody, 'x-www-form-urlencoded') !== false) {
        // Get data from form-data or x-www-form-urlencoded
        $data = [
            'temperature' => isset($_POST['temperature']) ? $_POST['temperature'] : null,
            'pln_volt' => isset($_POST['pln_volt']) ? $_POST['pln_volt'] : null,
            'pln_current' => isset($_POST['pln_current']) ? $_POST['pln_current'] : null,
            'pln_activity' => isset($_POST['pln_activity']) ? $_POST['pln_activity'] : null,
            'pln_status' => isset($_POST['pln_status']) ? $_POST['pln_status'] : null,
            'accu_volt' => isset($_POST['accu_volt']) ? $_POST['accu_volt'] : null,
            'accu_current' => isset($_POST['accu_current']) ? $_POST['accu_current'] : null,
            'accu_activity' => isset($_POST['accu_activity']) ? $_POST['accu_activity'] : null,
            'accu_status' => isset($_POST['accu_status']) ? $_POST['accu_status'] : null,
            //'ups_volt' => isset($_POST['ups_volt']) ? $_POST['ups_volt'] : null,
            //'ups_current' => isset($_POST['ups_current']) ? $_POST['ups_current'] : null,
            //'ups_activity' => isset($_POST['ups_activity']) ? $_POST['ups_activity'] : null,
            //'ups_status' => isset($_POST['ups_status']) ? $_POST['ups_status'] : null,
            'device_id' => isset($_POST['device_id']) ? $_POST['device_id'] : null,
            'soc' => isset($_POST['soc']) ? $_POST['soc'] : null,
            'accu_estimate_time' => isset($_POST['accu_estimate_time']) ? $_POST['accu_estimate_time'] : null,
            'accu_info' => isset($_POST['accu_info']) ? $_POST['accu_info'] : null
        ];
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
        'device_id', 'soc', 'accu_estimate_time', 'accu_info'
    ];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
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
            device_id, soc, accu_estimate_time, accu_info
        ) VALUES (
            :temperature, :pln_volt, :pln_current, :pln_activity, :pln_status,
            :accu_volt, :accu_current, :accu_activity, :accu_status,
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
        // ':ups_volt' => $data['ups_volt'],
        //':ups_current' => $data['ups_current'],
        //':ups_activity' => $data['ups_activity'],
        //':ups_status' => $data['ups_status'],
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
