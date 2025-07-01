<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Baca subscription dari file
$subscriptionJson = file_get_contents('subscription.json');
if (!$subscriptionJson) {
    die("No subscription available\n");
}
$subscription = Subscription::create(json_decode($subscriptionJson, true));
echo "Subscription loaded: " . $subscription->getEndpoint() . "\n";

// Masukkan VAPID key Anda (generate sesuai petunjuk)
$auth = [
    'VAPID' => [
        "subject" => "mailto:ferdyhahan5@gmail.com",
        "publicKey" => "BExsNSkVcnXa4i40_BncMYheo3uoYs9fHf92CqjX7f60PoE03i2n3KXaOToFo1B9Py0US6HxbKh16mlgQkjVF08",
        "privateKey" => "cBuCvNr0KEFxA7H68NoLWl0oIga_GcjeOJR914H3fjg"
    ],
];

// Payload notifikasi
$payload = json_encode([
    'title' => 'Hai dari Server!',
    'body' => 'Notifikasi push ini muncul walaupun kamu tidak membuka website.',
    'icon' => 'notification.png',
    // 'icon' => 'tiger.jpg',
    'image' => 'tiger.jpg'
]);

// Kirim notifikasi
$webPush = new WebPush($auth);
$report = $webPush->sendOneNotification($subscription, $payload);
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "Notifikasi berhasil dikirim ke endpoint: {$endpoint}\n";
    } else {
        echo "Gagal mengirim notifikasi ke endpoint: {$endpoint}\n";
        echo "Reason: " . $report->getReason() . "\n";
    }
}
