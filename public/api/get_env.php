<?php
require_once __DIR__ . '/../../bootstrap.php';

echo json_encode([
    'publicKey' => $_ENV['VAPID_PUBLIC_KEY'],
    'baseUrl' => $_ENV['BASE_URL'],
    'environment' => $_ENV['ENVIRONMENT']
]);
