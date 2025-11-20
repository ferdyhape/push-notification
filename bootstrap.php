<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Muat .env di root
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
