<?php
require_once __DIR__ . '/../bootstrap.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$title = $_POST['title'] ?? 'No Title';
$body = $_POST['body'] ?? 'No Body';
$icon = $_POST['icon'] ?? 'assets/images/notification.png';
$image = $_POST['image'] ?? '';

$subscriptionJson = file_get_contents(__DIR__ . '/../storage/subscription.json');
if (!$subscriptionJson) {
    die("No subscriptions available\n");
}

$subscriptionsArray = json_decode($subscriptionJson, true);
if (!is_array($subscriptionsArray) || count($subscriptionsArray) === 0) {
    die("No valid subscriptions found\n");
}

$auth = [
    'VAPID' => [
        "subject" => $_ENV['VAPID_SUBJECT'],
        "publicKey" => $_ENV['VAPID_PUBLIC_KEY'],
        "privateKey" => $_ENV['VAPID_PRIVATE_KEY']
    ],
];

$payload = json_encode([
    'title' => $title,
    'body' => $body,
    'icon' => $icon,
    'image' => $image
]);

$webPush = new WebPush($auth);

foreach ($subscriptionsArray as $subArray) {
    try {
        $subscription = Subscription::create($subArray);
        $webPush->sendOneNotification($subscription, $payload);
    } catch (Exception $e) {
        echo "Error with subscription endpoint: " . ($subArray['endpoint'] ?? 'unknown') . " - " . $e->getMessage() . "<br>";
    }
}

foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "Notification sent successfully to endpoint: {$endpoint}<br>";
    } else {
        echo "Failed to send notification to endpoint: {$endpoint}<br>";
        echo "Reason: " . $report->getReason() . "<br>";
    }
}
