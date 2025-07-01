<?php
$data = json_decode(file_get_contents('php://input'), true);
if ($data) {
    file_put_contents('subscription.json', json_encode($data));
    http_response_code(201);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid subscription data']);
}
