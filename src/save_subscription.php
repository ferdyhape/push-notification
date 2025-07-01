
<?php
$data = json_decode(file_get_contents('php://input'), true);
if ($data) {
    $file = __DIR__ . '/../storage/subscription.json';
    $subscriptions = [];
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $subscriptions = json_decode($content, true);
        if (!is_array($subscriptions)) {
            $subscriptions = [];
        }
    }

    $exists = false;
    foreach ($subscriptions as $sub) {
        if (isset($sub['endpoint']) && $sub['endpoint'] === $data['endpoint']) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $subscriptions[] = $data;
        file_put_contents($file, json_encode($subscriptions));
    }

    http_response_code(201);
    echo json_encode(['success' => true, 'message' => $exists ? 'Subscription already exists' : 'Subscription saved']);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid subscription data']);
}
