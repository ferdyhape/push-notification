<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Baca semua subscription (sekarang berupa array)
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
        echo "Notifikasi berhasil dikirim ke endpoint: {$endpoint}\n";
    } else {
        echo "Gagal mengirim notifikasi ke endpoint: {$endpoint}\n";
        echo "Reason: " . $report->getReason() . "\n";
    }
}

echo "All notifications processed.\n";

