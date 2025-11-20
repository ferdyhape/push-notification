<?php
$file = __DIR__ . '/../storage/subscription.json';
$subscriptions = [];

if (file_exists($file)) {
    $content = file_get_contents($file);
    $subscriptions = json_decode($content, true);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Push Subscription Viewer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding: 2rem;
        }

        .subscription-table {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .code {
            font-size: 0.85rem;
            word-break: break-word;
            background: #f1f3f5;
            padding: 0.75rem;
            border-radius: 8px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4">🔍 Subscription Viewer</h2>

        <?php if (count($subscriptions) === 0): ?>
            <div class="alert alert-info">No subscriptions found.</div>
        <?php else: ?>
            <div class="table-responsive subscription-table">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Endpoint</th>
                            <th>Keys</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscriptions as $i => $sub): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <div class="code"><?= htmlspecialchars($sub['endpoint']) ?></div>
                                </td>
                                <td>
                                    <div class="code"><strong>p256dh:</strong> <?= htmlspecialchars($sub['keys']['p256dh'] ?? '-') ?></div>
                                    <div class="code"><strong>auth:</strong> <?= htmlspecialchars($sub['keys']['auth'] ?? '-') ?></div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>