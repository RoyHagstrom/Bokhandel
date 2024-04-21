<?php
declare(strict_types=1);

ini_set('opcache.enable_cli', 1);
ini_set('opcache.validate_timestamps', 1);
ini_set('opcache.save_comments', 0);
ini_set('opcache.enable_file_override', 0);
ini_set('opcache.fast_shutdown', 1);

define('DB_HOSTS', [
    'primary' => '172.22.0.2',
    'local' => '192.168.1.111',
    'network' => 'novatest.ddns.net'
]);

define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

function connectToDb($host) {
    static $conns = [];
    if (isset($conns[$host])) {
        return $conns[$host];
    }

    $mysqli = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

    if ($mysqli->connect_error) {

        error_log('Connection error: ' . $mysqli->connect_error);
        throw new Exception('Could not connect to the database.');
    }

    $mysqli->set_charset('utf8mb4');
    $mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);

    $conns[$host] = $mysqli;
    return $mysqli;
}

function getDatabaseConnection() {
    $hosts = DB_HOSTS;
    foreach ($hosts as $host) {
        try {
            return connectToDb($host);
        } catch (Exception $e) {
            continue;
        }
    }
    throw new Exception('Could not connect to any database host.');
}

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

    $user = new USER($conn);
} catch (Exception $e) {

    error_log('Database connection error: ' . $e->getMessage());

    exit('Oops! Something went wrong. Please try again later.');
}
?>
