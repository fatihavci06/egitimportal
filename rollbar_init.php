<?php
require_once __DIR__ . '/vendor/autoload.php';

// Rollbar başlat
\Rollbar\Rollbar::init([
    'access_token' => 'd215743a5ce842c9823fe62b027d3882', // Kendi token
    'environment'  => 'production',           // development / production / staging
]);

// Opsiyonel: kullanıcı veya request bilgisi ekleme
\Rollbar\Rollbar::configure([
    'person' => [
        'id'       => $_SESSION['user_id'] ?? 'guest',
        'username' => $_SESSION['username'] ?? 'guest',
    ],
    'server' => [
        'host' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'root' => $_SERVER['DOCUMENT_ROOT'] ?? __DIR__,
    ]
]);
