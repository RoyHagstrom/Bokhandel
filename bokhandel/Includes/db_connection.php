<?php

define('DB_HOSTS', [
    'local' => 'mysql-db-1',
    'network' => 'novatest.ddns.net'
]);
define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');
define('DB_CONNECTION_TIMEOUT', 3);

/**
 * @return mysqli
 * @throws Exception 
 */
function getDatabaseConnection() {
    foreach (DB_HOSTS as $host) {
        $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        if ($conn->connect_errno === 0) {
            $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
            return $conn;
        } else {
            error_log("Connection failed to host $host: " . $conn->connect_error);
        }
    }
    throw new Exception("Could not get database connection");
}

ini_set('opcache.enable_cli', 1);
ini_set('opcache.validate_timestamps', 1);
ini_set('opcache.save_comments', 0);
ini_set('opcache.enable_file_override', 0);
ini_set('opcache.fast_shutdown', 1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $conn = getDatabaseConnection();
    $user = new USER($conn);
} catch (Exception $e) {
    error_log($e->getMessage());
    die("A critical error occurred. Please try again later.");
}
?>
