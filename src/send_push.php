<?php
require_once __DIR__ . '/../bootstrap.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

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
    'title' => 'Hello World!',
    'body' => 'This is a push notification sent to multiple subscribers.',
    'icon' => 'notification.png',
]);

$webPush = new WebPush($auth);

foreach ($subscriptionsArray as $subArray) {
    try {
        $subscription = Subscription::create($subArray);
        $report = $webPush->sendOneNotification($subscription, $payload);
    } catch (Exception $e) {
        echo "Error with subscription endpoint: " . ($subArray['endpoint'] ?? 'unknown') . " - " . $e->getMessage() . "\n";
    }
}

// Pastikan semua notifikasi terkirim dengan flush
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "Notification sent successfully to endpoint: {$endpoint}\n";
    } else {
        echo "Failed to send notification to endpoint: {$endpoint}\n";
        echo "Reason: " . $report->getReason() . "\n";
    }
}

echo "All notifications processed.\n";
