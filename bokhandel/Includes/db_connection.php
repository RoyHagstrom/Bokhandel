<?php

declare(strict_types=1);

define('DB_HOSTS', [
    'primary' => '172.22.0.2',
    'local' => '192.168.1.111',
    'network' => 'novatest.ddns.net'
]);

define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

function connectToDb(string $host): mysqli
{
    $mysqli = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, (int) DB_PORT);

    if ($mysqli->connect_error) {
        throw new Exception('Could not connect to the database: ' . $mysqli->connect_error);
    }

    $mysqli->set_charset('utf8mb4');
    $mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);

    return $mysqli;
}

function getDatabaseConnection(): mysqli
{
    foreach (DB_HOSTS as $host) {
        try {
            return connectToDb($host);
        } catch (Exception $e) {
            continue;
        }
    }

    throw new Exception('Could not connect to any database host.');
}

ini_set('opcache.enable_cli', 1);
ini_set('opcache.validate_timestamps', 1);
ini_set('opcache.save_comments', 0);
ini_set('opcache.enable_file_override', 0);
ini_set('opcache.fast_shutdown', 1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_lifetime' => 3600,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'cookie_secure' => true,
    ]);
}

try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    exit('Oops! Something went wrong. Please try again later.');
}

try {
    $user = new USER($conn);
} catch (Exception $e) {
    error_log('User object creation error: ' . $e->getMessage());
    exit('Oops! Something went wrong. Please try again later.');
}