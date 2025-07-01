<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Ambil data input dari form
$title = $_POST['title'] ?? 'No Title';
$body = $_POST['body'] ?? 'No Body';
$icon = $_POST['icon'] ?? 'notification.png'; // Default icon jika tidak diisi

// Ambil data subscription
$subscriptionJson = file_get_contents('subscription.json');
if (!$subscriptionJson) {
    die("No subscriptions available\n");
}

$subscriptionsArray = json_decode($subscriptionJson, true);
if (!is_array($subscriptionsArray) || count($subscriptionsArray) === 0) {
    die("No valid subscriptions found\n");
}

$auth = [
    'VAPID' => [
        "subject" => "mailto:ferdyhahan5@gmail.com",
        "publicKey" => "BExsNSkVcnXa4i40_BncMYheo3uoYs9fHf92CqjX7f60PoE03i2n3KXaOToFo1B9Py0US6HxbKh16mlgQkjVF08",
        "privateKey" => "cBuCvNr0KEFxA7H68NoLWl0oIga_GcjeOJR914H3fjg"
    ],
];

$payload = json_encode([
    'title' => $title,
    'body' => $body,
    'icon' => $icon,
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

// Flush all queued messages
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "Notification sent successfully to endpoint: {$endpoint}<br>";
    } else {
        echo "Failed to send notification to endpoint: {$endpoint}<br>";
        echo "Reason: " . $report->getReason() . "<br>";
    }
}
